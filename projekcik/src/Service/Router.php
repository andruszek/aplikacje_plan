<?php
namespace App\Service;

class Router
{
    public function generatePath(string $action, ?array $params = []): string
    {
        $query = $action ? http_build_query(array_merge(['action' => $action], $params)) : null;
        $path = "/index.php" . ($query ? "?$query" : null);
        return $path;
    }

    public function generateFilePath(string $relativePath): string
    {
        // Ustal bazowy katalog projektu, np. katalog główny
        $basePath = dirname(__DIR__, 2); // Dwa poziomy wyżej od folderu "Service"

        // Zbuduj pełną ścieżkę, łącząc bazowy katalog i podaną ścieżkę względną
        $filePath = $basePath . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);

        return $filePath;
    }

    public function redirect($path): void
    {
        header("Location: $path");
    }
}
