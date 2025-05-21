<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'make:upload-key')]
class GenerateUploadKeyCommand extends Command
{
    use ConfirmableTrait;

    protected $signature = 'make:upload-key {--show : Display the key instead of modifying files}';

    protected $description = 'Generate a key to upload images.';

    public function handle(): ?string
    {
        $key = Str::random(64);

        if ($this->option('show')) {
            return $this->line('<comment>'.$key.'</comment>');
        }

        if (!$this->setKeyInEnvironmentFile($key)) {
            return null;
        }

        $this->components->info('Upload key set successfully.');
    }

    protected function setKeyInEnvironmentFile($key): bool
    {
        $currentKey = $this->laravel['config']['app.upload_key'];

        if (strlen($currentKey) !== 0 && (!$this->confirmToProceed())) {
            return false;
        }

        if (!$this->writeNewEnvironmentFileWith($key)) {
            return false;
        }

        return true;
    }

    protected function writeNewEnvironmentFileWith($key): bool
    {
        $replaced = preg_replace(
            $this->keyReplacementPattern(),
            'APP_UPLOAD_KEY='.$key,
            $input = file_get_contents($this->laravel->environmentFilePath())
        );

        if ($replaced === $input || $replaced === null) {
            $this->error('Unable to set application key. No APP_UPLOAD_KEY variable was found in the .env file.');

            return false;
        }

        file_put_contents($this->laravel->environmentFilePath(), $replaced);

        return true;
    }

    /**
     * Get a regex pattern that will match env APP_KEY with any random key.
     *
     * @return string
     */
    protected function keyReplacementPattern(): string
    {
        $escaped = preg_quote('='.$this->laravel['config']['app.upload_key'], '/');

        return "/^APP_UPLOAD_KEY{$escaped}/m";
    }
}
