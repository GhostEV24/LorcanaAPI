<?php

namespace App\Console\Commands;

use App\Models\Card;
use App\Models\Set;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

use function Laravel\Prompts\error;
use function Laravel\Prompts\info;
use function Laravel\Prompts\progress;

class ImportData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permet de récupérer gentillement les données d\'une autre API';

    private $useCache = false;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $url = "https://lorcanajson.org/files/current/fr/allCards.json";
        //Création de la requête
        //Récupération du body (en json)
        if ($this->useCache) {
            $data = json_decode(file_get_contents(storage_path("app/data.json")));
        } else {
            $request = Http::get($url);
            $data = json_decode($request->body());
            //Save in file
            file_put_contents(storage_path("app/data.json"), json_encode($data));
        }

        $sets = $data->sets;
        $cards = $data->cards;

        $this->syncSets($sets);
        $this->syncCards($cards);

        return;
    }

    private function syncCards($data)
    {
        $cards = [];
        $setIdsByApiIds = Set::pluck("id", "api_id");
        foreach ($data as $key => $value) {
            $cards[] = [
                "api_id" => $value->id,
                "set_id" => $setIdsByApiIds[$value->setCode],
                "name" => $value->name,
                "number" => $value->number,
                "version" => $value->version ?? "",
                "cardIdentifier" => $value->fullIdentifier,
                "description" => $value->fullText,
                "image" =>  $value->images->full,
                "thumbnail" => $value->images->thumbnail,
                "rarity" => $value->rarity,
                "story" => $value->story,
            ];
        }

        progress(
            "Enregistrement des cartes",
            $cards,
            function ($apiItem) {
                Card::updateOrCreate(
                    [
                        "api_id" => $apiItem["api_id"]
                    ],
                    $apiItem
                );
            }
        );
    }

    private function syncSets($data)
    {
        $sets = [];
        //Transform $sets object to array
        $data = json_decode(json_encode($data), true);
        //Extract array key to add item id in array
        foreach ($data as $key => $value) {
            $sets[] = [
                "api_id" => $key,
                "name" => $value["name"],
                "code" => $key,
                "type" => $value["type"],
                "release_date" => $value["releaseDate"],
                "prerelease_date" => $value["prereleaseDate"] ?? null
                
            ];
        }

        progress(
            "Enregistrement des chapitres",
            $sets,
            function ($apiItem) {
                // Upsert in DB : update if exists, create if not
                Set::updateOrCreate(
                    ["api_id" => $apiItem["api_id"]],
                    [
                        "name" => $apiItem["name"] ?? '',
                        "code" => $apiItem["code"] ?? '',
                        "release_date" => $apiItem["release_date"] ?? null,
                        "prerelease_date" => $apiItem["prerelease_date"] ?? null // Correction ici
                    ]
                );                
            }
        );
    }
}
