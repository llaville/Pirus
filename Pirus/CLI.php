<?php
/**
 * Pirus is the companion of Pirum to handle look and feel of your web page.
 *
 * PHP version 5
 *
 * @category Pirum
 * @package  Pirus
 * @author   Laurent Laville <pear@laurent-laville.org>
 * @license  http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version  GIT: $Id$
 * @link     http://php5.laurent-laville.org/pirus/
 */

require_once 'Console/CommandLine.php';
require_once 'Console/CommandLine/Option.php';
require_once 'Console/CommandLine/Argument.php';

require_once 'pirum';

/**
 * Command Line Interface of Pirus
 *
 * @category Pirum
 * @package  Pirus
 * @author   Laurent Laville <pear@laurent-laville.org>
 * @license  http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version  Release: @package_version@
 * @link     http://php5.laurent-laville.org/pirus/
 */
class Pirus_CLI extends Pirum_CLI
{
    /**
     * Current version of Pirus
     * @var string
     */
    const VERSION = '@package_version@';

    /**
     * Gets current version of Pirus
     *
     * @return string
     */
    public static function getVersion()
    {
        if (strpos(self::VERSION, '@package_version') === 0) {
            $version = 'DEV';
        } else {
            $version = self::VERSION;
        }
        return $version;
    }

    /**
     * Handle console input and run a Pirum instance
     *
     * @return void
     */
    public static function main()
    {
        $input = new Console_CommandLine(
            array(
                'name'        => 'pirus',
                'description' => 'Pirus (cli) by Laurent Laville.',
                'version'     => self::getVersion()
            )
        );

        // common options to all commands
        $themeOption = new Console_CommandLine_Option(
            'theme',
            array(
                'short_name'  => '-t',
                'long_name'   => '--theme',
                'action'      => 'StoreString',
                'description' => 'The name of the theme (HTML/CSS) to apply.',
            )
        );
        $templatesDirOption = new Console_CommandLine_Option(
            'templatesDir',
            array(
                'short_name'  => '-d',
                'long_name'   => '--templatesDir',
                'action'      => 'StoreString',
                'description' => 'Base directory of all the Pirum themes.',
            )
        );

        // common arguments to all commands
        $targetDirArgument = new Console_CommandLine_Argument(
            'targetDir',
            array(
                'description' => 'The root directory of the PEAR channel server.'
            )
        );

        // arguments for add and remove commands
        $pearPackageArgument = new Console_CommandLine_Argument(
            'pearPackage',
            array(
                'description' => 'The PEAR package name.'
            )
        );
        
        // all commands
        $buildCmd = $input->addCommand(
            'build',
            array(
                'description' => 'Build the full content of the target directory.'
            )
        );
        $buildCmd->addOption($themeOption);
        $buildCmd->addOption($templatesDirOption);
        $buildCmd->addArgument($targetDirArgument);

        $addCmd = $input->addCommand(
            'add',
            array(
                'description' => 'Add a new release.'
            )
        );
        $addCmd->addOption($themeOption);
        $addCmd->addOption($templatesDirOption);
        $addCmd->addArgument($targetDirArgument);
        $addCmd->addArgument($pearPackageArgument);

        $removeCmd = $input->addCommand(
            'remove',
            array(
                'description' => 'Remove an old release.'
            )
        );
        $removeCmd->addOption($themeOption);
        $removeCmd->addOption($templatesDirOption);
        $removeCmd->addArgument($targetDirArgument);
        $removeCmd->addArgument($pearPackageArgument);

        // run the command
        try {
            $result = $input->parse();
            $command = $result->command_name;

            if (empty($command)) {
                $input->displayUsage(1);
            }
        }
        catch (Exception $e) {
            $input->displayError($e->getMessage());
        }

        // select default options about theme
        $cfg = '@cfg_dir@/pirus/pirus.ini';

        if (file_exists($cfg)) {
            $conf = parse_ini_file($cfg, true);
        } else {
            $conf = array(
                'templates' => array(
                    'dir'   => '@data_dir@/pirus/templates',
                    'theme' => 'default'
                )
            );
        }

        // select the theme to apply
        if (isset($result->command->options['theme'])) {
            $theme = $result->command->options['theme'];
        } else {
            $theme = $conf['templates']['theme'];
        }

        // select the base root directory of all themes
        if (isset($result->command->options['templatesDir'])) {
            $templatesDir = $result->command->options['templatesDir'];
        } else {
            $templatesDir = $conf['templates']['dir'];
        }

        // select your PEAR channel server root directory
        $targetDir = $result->command->args['targetDir'];

        // select the PEAR package for add and remove commands
        if (isset($result->command->args['pearPackage'])) {
            $pearPackage = $result->command->args['pearPackage'];
        } else {
            $pearPackage = null;
        }
        
        $options = array(
            0 => $templatesDir . DIRECTORY_SEPARATOR . $theme,
            1 => $command,
            2 => $targetDir,
            3 => $pearPackage,
        );
        $pirum = new self($options);
        $pirum->run();
    }

    /**
     * Checks that all components (html, css, dir) of a theme exists
     * before running the Pirum commands.
     *
     * @return void
     */
    public function run()
    {
        $themeDir = $this->options[0];

        if (!is_dir($themeDir)) {
            $this->getUsage();
            echo $this->formatter->formatSection(
                'ERROR', "Templates directory '$themeDir' does not exists."
            );
            return 1;
        }
        if (!file_exists($themeDir . DIRECTORY_SEPARATOR . 'index.html')) {
            $this->getUsage();
            echo $this->formatter->formatSection(
                'ERROR', "Template 'index.html' does not exists."
            );
            return 1;
        }
        if (!file_exists($themeDir . DIRECTORY_SEPARATOR . 'pirum.css')) {
            $this->getUsage();
            echo $this->formatter->formatSection(
                'ERROR', "Template 'pirum.css' does not exists."
            );
            return 1;
        }

        parent::run();
    }

    /**
     * Provides the theme directory at the Pirum build command
     *
     * @param string $target The root directory of the PEAR channel server
     *
     * @return void
     */
    protected function runBuild($target)
    {
        $builder = new Pirus_Builder($this->options[0], $target, $this->formatter);
        $builder->build();
    }

    /**
     * Prints the header of both tools Pirus and Pirum
     *
     * @return void
     */
    protected function getUsage()
    {
        echo $this->formatter->format(
            sprintf("Pirus %s by Laurent Laville\n", self::getVersion()), 'INFO'
        );
        echo $this->formatter->format(
            sprintf("Pirum %s by Fabien Potencier\n", Pirum_CLI::version()), 'INFO'
        );
    }

}

/**
 * Delegate the build of template files (html, css) of the Pirum Builder process
 *
 * @category Pirum
 * @package  Pirus
 * @author   Laurent Laville <pear@laurent-laville.org>
 * @license  http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version  Release: @package_version@
 * @link     http://php5.laurent-laville.org/pirus/
 */
class Pirus_Builder extends Pirum_Builder
{
    protected $themeDir;

    /**
     * Creates an instance of the Pirum Builder that will handle
     * the Pirus template files.
     *
     * @param string $themeDir  The directory of the selected theme
     * @param string $targetDir The root directory of the PEAR channel server
     * @param object $formatter The command line colorizer for Pirum
     *
     * @return Pirus_Builder
     */
    public function __construct($themeDir, $targetDir, $formatter)
    {
        $this->themeDir = $themeDir;
        parent::__construct($targetDir, $formatter);
    }

    /**
     * Build the pirum.css file of the selected theme
     * in the root directory of the PEAR channel server
     *
     * @return void
     */
    protected function buildCss()
    {
        copy($this->themeDir.'/pirum.css', $this->buildDir.'/pirum.css');
    }

    /**
     * Build the index.html file of the selected theme
     * in the root directory of the PEAR channel server
     *
     * @return void
     */
    protected function buildIndex()
    {
        if ($this->formatter) {
            print $this->formatter->formatSection('INFO', "Building index.");
        }

        $version = Pirum_CLI::version();

        $file = $this->themeDir.'/index.html';
        ob_start();
        include $file;
        $html = ob_get_clean();
        file_put_contents($this->buildDir.'/index.html', $html);
    }
}
