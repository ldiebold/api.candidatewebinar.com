<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class RegisterModelsFromSchemaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $directory = base_path('../orm-classes/src/schemas');
        $scanned_directory = array_diff(scandir($directory), array('..', '.'));

        $classNames = collect($scanned_directory)->map(function ($fileName) {
            return (string) Str::of($fileName)
                ->replace('.json', '');
        });


        $classes = $classNames->map(function ($className) {
            return "\App\\Models\\" . $className;
        })->filter(function ($className) {
            return class_exists($className);
        });


        $morphMaps = $classes->mapWithKeys(function ($className) {

            return [$className::getSchema()['entity'] => $className];
        });

        Relation::morphMap($morphMaps->toArray());



        $classes->each(function ($class) {
            $schema = $class::getSchema();
            if (!array_key_exists('relationships', $schema)) {
                return;
            }

            $relationships = collect($schema["relationships"]);

            $relationships->each(function ($relationship, $key) use ($class) {
                $class::resolveRelationUsing($key, function ($model) use ($relationship) {
                    $relationshipType = $relationship["type"];
                    return $model->$relationshipType(
                        Relation::getMorphedModel($relationship["params"][0]),
                        ...array_slice($relationship["params"], 1)
                    );
                });
            });
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
