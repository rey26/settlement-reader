<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;

class FileHandler
{
    public function __construct(#[Autowire(param: 'coat_of_arms_path')] protected string $coatOfArmsPath)
    {
    }

    public function downloadFile(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }
        $localFileName = basename($url);
        $localPath = $this->coatOfArmsPath . '/' . $localFileName;

        // Check if the file already exists locally
        if (file_exists($localPath)) {
            return $localPath;
        }

        // Download the file from the URL
        $fileContents = file_get_contents($url);

        if ($fileContents === false) {
            return null; // Failed to download the file
        }

        // Save the file locally
        file_put_contents($localPath, $fileContents);

        return $localPath;
    }
}
