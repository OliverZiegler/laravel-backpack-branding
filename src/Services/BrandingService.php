<?php

namespace Zoutapps\Laravel\Backpack\Branding\Services;

use Illuminate\Filesystem\Filesystem;
use Zoutapps\Laravel\Backpack\Branding\Commands\BrandingCommand;
use Zoutapps\Laravel\Backpack\Branding\Helper\ShortSyntaxArray;

class BrandingService
{
    private $command;
    private $filesystem;
    private $defaults;

    public function __construct(BrandingCommand $command)
    {
        $this->command = $command;
        $this->defaults = $this->command->getDefaults('branding');
        $this->filesystem = new Filesystem();
    }

    public function perform()
    {
        $this->writeConfigFile($this->defaults->toArray());

        return true;
    }

    function writeConfigFile(array $values)
    {
        $content = $this->fileContent($values);

        $path = base_path('config/zoutapps/branding.php');

        if (!$this->filesystem->isDirectory(base_path('config/zoutapps'))) {
            $this->filesystem->makeDirectory(base_path('config/zoutapps'));
        }

        $this->filesystem->put($path, $content);

        $this->command->info("Config file create at <comment>{$path}</comment>");
    }

    function fileContent($values)
    {
        $data = ShortSyntaxArray::parse($values);

        return <<<FILE
<?php

// Autogenerated config file with zoutapps/laravel-backpack-branding used for Branding Facade

return {$data};
FILE;
    }
}