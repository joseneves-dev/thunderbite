<?php

namespace App\Helpers;

class JsonHelper
{
    /**
     * Create a JSON configuration with dynamic parameters.
     *
     * @param string|null $message
     * @param int|null $gameId
     * @param array|null $revealedTiles
     * @param string $apiPath
     * @return string
     */
    public static function createConfig(
        ?string $message = null,
        ?int $gameId = null,
        ?array $revealedTiles = null,
    ): string {
        $jsonConfig = [];

        // Add parameters only if they are provided
        if ($message !== null) {
            $jsonConfig['message'] = $message;
        }

        if ($gameId !== null) {
            $jsonConfig['gameId'] = $gameId;
        }

        if ($revealedTiles !== null) {
            //Error misspeling!
            $jsonConfig['reveledTiles'] = $revealedTiles;
        }

        // Always include apiPath if not provided
        $jsonConfig['apiPath'] = '/api/flip';

        return json_encode($jsonConfig);
    }
}
