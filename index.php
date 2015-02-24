<!DOCTYPE html>
<html>
<head>
    <style type="text/css">
    *{font:16px/1.6 Helvetica,Arial,freesans,sans-serif;}
    p,pre,h1,h2,h3,form,div{width:58%;}
    h1,h2,h3{font-weight:bold;border-bottom:1px solid #eee;}
    h1{font-size:2.25em;line-height:1.2;}h2{font-size:1.75em;line-height:1.225;}
    pre,code{font:85%/1.6 Consolas,"Liberation Mono",monospace;background:#eee;}
    code{word-wrap:normal;}
    pre{padding:16px;overflow:auto;}code{padding:0.2em;}
    a{color:#4183C4;text-decoration:none;}a:hover{text-decoration:underline;}
    em{font-style:italic;color:gray;}
    form{padding:16px;}
    form.environment:hover{background:#eee}
    pre#output{width:100%;background:#300a24;color:#d0bfb5;}
    form.output{position:absolute;z-index:1;text-align:right;}
    input[name=output]{margin-top:10px;color:white;font-size:30px;
        background:transparent;border:0;cursor:pointer}
    input[name=output]:hover{color:#fffd98}
    form.actual{background: #ffeef7;}
    p.hint{padding:16px;background: #fffd98;font-style:italic;margin-top:0;}
    p.first{margin-bottom:0;margin-top:16px;}
    .readme{position:absolute;padding:0 0 15px 15px;left:60%;top:0;width:63%;
        background:white;
        -webkit-box-shadow: -4px 4px 5px 0px rgba(50, 50, 50, 0.5);
        -moz-box-shadow:    -4px 4px 5px 0px rgba(50, 50, 50, 0.5);
        box-shadow:         -4px 4px 5px 0px rgba(50, 50, 50, 0.5);}
    body{overflow-x:hidden}
    .animated{-webkit-animation-duration:1s;animation-duration:1s;-webkit-animation-fill-mode:both;animation-fill-mode:both}
    .animated.infinite{-webkit-animation-iteration-count:infinite;animation-iteration-count:infinite}
    .animated.hinge{-webkit-animation-duration:2s;animation-duration:2s}
    @-webkit-keyframes bounce {
    0%,20%,53%,80%,100%{-webkit-transition-timing-function:cubic-bezier(0.215,0.610,0.355,1.000);transition-timing-function:cubic-bezier(0.215,0.610,0.355,1.000);-webkit-transform:translate3d(0,0,0);transform:translate3d(0,0,0)}
    40%,43%{-webkit-transition-timing-function:cubic-bezier(0.755,0.050,0.855,0.060);transition-timing-function:cubic-bezier(0.755,0.050,0.855,0.060);-webkit-transform:translate3d(0,-30px,0);transform:translate3d(0,-30px,0)}
    70%{-webkit-transition-timing-function:cubic-bezier(0.755,0.050,0.855,0.060);transition-timing-function:cubic-bezier(0.755,0.050,0.855,0.060);-webkit-transform:translate3d(0,-15px,0);transform:translate3d(0,-15px,0)}
    90%{-webkit-transform:translate3d(0,-4px,0);transform:translate3d(0,-4px,0)}
    }
    @keyframes bounce {
    0%,20%,53%,80%,100%{-webkit-transition-timing-function:cubic-bezier(0.215,0.610,0.355,1.000);transition-timing-function:cubic-bezier(0.215,0.610,0.355,1.000);-webkit-transform:translate3d(0,0,0);transform:translate3d(0,0,0)}
    40%,43%{-webkit-transition-timing-function:cubic-bezier(0.755,0.050,0.855,0.060);transition-timing-function:cubic-bezier(0.755,0.050,0.855,0.060);-webkit-transform:translate3d(0,-30px,0);transform:translate3d(0,-30px,0)}
    70%{-webkit-transition-timing-function:cubic-bezier(0.755,0.050,0.855,0.060);transition-timing-function:cubic-bezier(0.755,0.050,0.855,0.060);-webkit-transform:translate3d(0,-15px,0);transform:translate3d(0,-15px,0)}
    90%{-webkit-transform:translate3d(0,-4px,0);transform:translate3d(0,-4px,0)}
    }
    .bounce{-webkit-animation-name:bounce;animation-name:bounce;-webkit-transform-origin:center bottom;-ms-transform-origin:center bottom;transform-origin:center bottom}
    </style>
</head>
<body>
    <?php main(); $base_url = '';

    foreach ($environments as $item):

    ?>
    <?php if (@$_SESSION['environment'] === $item['name'] && @$_SESSION['output']): ?>
    <a name="active"></a>
    <?php endif; ?>
    <form method="POST" class="environment<?=@$_SESSION['environment'] === $item['name'] && @$_SESSION['output'] ? ' actual' : '' ?>">
        <input type="hidden" name="environment" value="<?=$item['name']?>">
        <a href="<?=($url = $base_url . $item['name'])?>"><?=$url?></a>
        is currently using branch
        <a href="<?=@$item['github'] . $item['branch']?>"><?=$item['branch']?></a>
        <br>
        <em>(last time updated <?=date('Y-m-d H:i:s', $item['modified'])?>)</em>

        <?php if (@$item['change_branch']): ?>
        <select name="change_branch"
            onchange="javascript:confirm_post('<?=$item['name']?>', this);">
            <option value="" disabled selected>Change branch...</option>
            <?php

            $branches = get_branches($item['path']);
            foreach ($branches as $branch):

            ?>
            <option<?=$branch === $item['branch'] ? ' disabled' : ''?>>
                <?=$branch?>
            </option>
            <?php

            endforeach;

            ?>
        </select>
        <?php endif; ?>

        <?php foreach (@$item['commands'] as $name => $code): ?>
        <input type="submit" name="<?=$code?>" value="<?=$name?>">
        <?php endforeach; ?>

    </form>

    <?php if (@$_SESSION['environment'] === $item['name'] && @$_SESSION['output']): ?>
    <form method="POST"class="output">
    <input type="submit" name="output" value="&#x2716;" title="Hide Output">
    </form>
    <pre id="output" class="animated bounce"><?=$_SESSION['output']?></pre>
    <?php endif; ?>
    <?php

    endforeach;

    ?>

    <?php if ($readme): ?>
    <div class="readme"><?=$readme?></div>
    <?php endif; ?>

</body>
<script type="text/javascript">
    function confirm_post(environment, select)
    {
        if (confirm(
            'Please confirm that you\'d like switch testing environment ' +
            environment + ' to ' + select.options[select.selectedIndex].value +
            ' branch. This operation clears database and load it with ' +
            'default data. All assets, cached and temporary data would be ' +
            'lost. Would you like to continue?'))
        {
            select.form.submit();
        }
    }
</script>
</html>
<?php

/**
 * Initialize script and handles all requests. It must be called before all
 * other php code in the script.
 */
function main()
{
    global $environments, $config;

    session_start();
    load_config();
    load_environments();

    $output = '';

    $environment = isset($_POST['environment']) ? $_POST['environment'] : '';

    if ($environment)
    {
        if (!isset($environments[$environment])) exit;

        $item = $environments[$environment];
        if (@$item['change_branch']) $item['commands'][''] = 'change_branch';

        foreach ($item['commands'] as $name => $command)
        {
            if (!isset($_POST[$command])) continue;

            if (isset($config['commands'][$command]))
            {
                if ($command === 'change_branch')
                {
                    $item['target'] = $_POST[$command];
                }

                $output = exec_script($config['commands'][$command], $item);

                $_SESSION['environment'] = $item['name'];
                $_SESSION['output'] = $output;

                @ob_end_clean();
                header('Location: ./' . basename(__FILE__) . '#active');
            }

            break;
        }

        exit;
    }
    else if (isset($_POST['output']))
    {
        if (isset($_SESSION['environment'])) unset($_SESSION['environment']);
        if (isset($_SESSION['output'])) unset($_SESSION['output']);

        @ob_end_clean();
        header('Location: ./' . basename(__FILE__));
    }
}

/**
 * Loads config into global variable.
 *
 * This function contains basic config that might be extended and overriden. You
 * can create index.config.php that returns array with script options. These
 * options will be merged with basic config. By default there are only one
 * type of testing environments and it has only two options - change branch and
 * update files with latest changes of the branch.
 */
function load_config()
{
    global $base_path, $config, $readme;

    $config = array (

        'environments' => array (
            '*' => array (
                'github' => 'https://github.com/Shopfans/gitflow-switch/tree/',
                'change_branch' => true,
                'commands' => array (
                    'Update' => 'update_branch',
                ),
            ),
        ),

        'commands' => array (
            'change_branch' =>
                '
                cd PATH
                git fetch                       # CMD 2>&1
                git checkout TARGET             # CMD 2>&1
                git pull origin TARGET          # CMD 2>&1
                ',
            'update_branch' =>
                '
                cd PATH
                git pull                        # CMD 2>&1
                ',
        ),
    );

    $base_path = getcwd();
    $config_path = $base_path . DIRECTORY_SEPARATOR . basename(__FILE__, '.php')
        . '.config.php';

    if (file_exists($config_path))
    {
        $output = ob_get_clean();
        ob_start();
        $loaded = @include($config_path);
        $readme = trim(ob_get_clean());
        @ob_end_clean();

        ob_start();
        echo $output;

        if ($readme) $readme = text_to_html($readme);
        if (is_array($loaded)) $config = array_merge($config, $loaded);
    }
    else
    {
        $readme = text_to_html(trim(default_readme()));
    }
}

/**
 * Converts formatted text into HTML.
 *
 * This converter assume that text formatted with something like Markdown. It's
 * very simple and support only basic features - headers, paragraphs, links,
 * preformatted text and inline code.
 *
 * @param string $text text to convert into html
 *
 * @return string HTML
 */
function text_to_html($text)
{
    return preg_replace(
        array (
            '%^(#+)\s+(.+)$%me', // convert headers
            '%\n{2,}(.+)(?=\n\n)%Us', // convert paragraphs
            '%<p>(\s{4}[^\s]+.+)</p>%Us', // convert <p> with code to <pre>
            '%<p>\s*(?=<h\d+>)|(?<=</h\d>)\s*</p>%s', // remove <p> around <h>
            '%</pre>\s*<pre>%s', '%`([^\`]+)`%U', // join a few <pre> together
            '%\(c\)%', // convert copyright into HTML entity
            '%\[([^\]]+)\]\s*\(([hf]t?tps?[^\)]+)\)%', // convert full links
            '%\[([^\]]+)\]\s*\(([^\)]+)\)%', // convert relative links
        ),
        array (
            '"\n<h".strlen("$1").">$2</h".strlen("$1").">\n"', "\n<p>\$1</p>\n",
            "<pre>\n\$1</pre>", '', "\n", '<code>$1</code>', '&copy;',
            '<a href="$2" target="_blank">$1</a>', '<em title="$2">$1</em>',
        ),
        $text . "\n\n");
}

/**
 * Execute a few commands like a script
 *
 * This function accept text with commands, array with commands or callable
 * function which takes environment data, executes commands and returns output.
 *
 * If the array used, then keys must be comment to show into output and the
 * value is a command to execute. This would be helpful if some command output
 * need to be filtered. In this case case the command to show available as
 * variable CMD in the command to execute. Just to write less code.
 *
 * The same feature with command to show and to execute available with commands
 * passed as text. In this case the part of each line before " # " (space, dash,
 * space) is a command to show, and the other side if a command to execute.
 *
 * In both command to show and command to execute you can use all data from
 * environment model, but with names in upper case:
 *
 * - PATH is a path to the environment
 * - NAME is a name of testing environment, e.g. base name of the PATH
 * - BRANCH is a name of the current branch
 * - TARGET is a name of the target branch
 * - GITHUB is an URL to the github tree
 * - MODIFIED is a time stamp when the testing environment updated last time
 *
 * All these strings will be replaced with actual values in the commands.
 *
 * @param string $script all commands to execute
 * @param array  $environment all environment options - branch name, path, etc
 *
 * @return string console output of all commands
 */
function exec_script($text, $environment)
{
    global $base_path;

    // the simplest way is using callback
    if (is_callable($text)) return call_user_func($text, $environment);

    $commands = array ();

    if (!is_array($text))
    {
        $text_commands = preg_split('/[\r\n]+/', $text);

        foreach ($text_commands as $line)
        {
            // skip empty lines and comments
            $line = trim($line);
            if (empty($line) || $line{0} === '#') continue;

            if ($comment = strpos($line, ' # '))
            {
                $command = trim(substr($line, 0, $comment));
                $full_command = trim(substr($line, $comment + 3));
                $commands[$command] = $full_command;
            }
            else
            {
                $commands[$line] = $line;
            }
        }
    }
    else
    {
        foreach ($text as $command => $full_command)
        {
            if (is_int($command) || ctype_digit((string) $command))
            {
                $command = $full_command;
            }
            $commands[$command] = $full_command;
        }
    }

    $output = array ();
    $origin = $path = $base_path;
    $prompt = '$ ';

    foreach ($environment as $id => $data)
    {
        if (!is_scalar($data)) unset($environment[$id]);
    }

    foreach ($commands as $command => $full_command)
    {
        $vars = array_map('strtoupper', array_keys($environment));
        $values = array_values($environment);
        $command = str_replace($vars, $values, $command);
        $full_command = str_replace('CMD', $command, $full_command);
        $full_command = str_replace($vars, $values, $full_command);

        if (strncmp('cd ', $command, 3) === 0)
        {
            $output[] = $prompt . $command;
            if (!@chdir($path = trim(substr($command, 2))))
            {
                $output[] = 'Can not change dir to ' . $path;
            }
        }
        else
        {
            $output[] = $prompt . $command;
            $output[] = trim(`$full_command`);
        }
    }

    if ($origin !== $path) @chdir($origin);

    return implode(PHP_EOL, $output);
}

/**
 * Returns a path to the testing environment.
 *
 * It's very simple function that usually makes two things - verify that
 * environment directory exists and terminate script with notification if
 * environment not found.
 *
 * @param string $environment name of environment
 * @param bool $exit_on_error (optional) show notification and exit if any error
 *
 * @return string path fo environment
 */
function get_environment_path($environment, $exit_on_error = true)
{
    global $base_path;

    $path = $base_path . DIRECTORY_SEPARATOR . $environment;

    if (!is_dir($path))
    {
        if ($exit_on_error)
        {
            sprintf("Unknown testing environment \"%s\"\n", $environment);

            exit(1);
        }

        $path = '';
    }

    return $path;
}

/**
 * Returns list of all available branches in the testing environment.
 *
 * The output completely depends on the branch status, you might need to update
 * it to get latest branches here.
 */
function get_branches($path)
{
    $branches = array_map('trim', array_map('basename', glob($path .
        '/.git/logs/refs/remotes/origin/*')));

    foreach ($branches as $i => $branch)
    {
        if ($branch === 'HEAD')
        {
            unset($branches[$i]);
            break;
        }
    }

    return $branches;
}

/**
 * Loads into global variable list of all found environments
 *
 * This function goes over all directories under base path (i.e. where is the
 * script run) and check if the directory has .git subdirecory. All these
 * directories assumed as testing environments. Then function call other helpers
 * to find more details about the environment and check config for the commands
 * available here.
 */
function load_environments()
{
    global $base_path, $environments, $config;

    $environments = array ();
    $files = glob($base_path . DIRECTORY_SEPARATOR . '*');

    foreach ($files as $path)
    {
        if (!is_dir($path) || !is_dir($path . '/.git')) continue;
        if (!file_exists($file = $path . '/.git/HEAD')) continue;

        foreach ($config['environments'] as $pattern => $environment)
        {
            if ($pattern !== '*' && !preg_match($pattern, $path)) continue;

            $environments[basename($path)] = $environment + array (
                'path'     => $path,
                'name'     => basename($path),
                'modified' => filemtime($path . '/.git'),
                'branches' => get_branches($path),
                'branch'   => trim(preg_replace('%^.+/%', '',
                    file_get_contents($file))),
            );

            break;
        }
    }

    uasort($environments,
        create_function('$a, $b', 'return strcmp($a["name"], $b["name"]);'));

    return $environments;
}

function default_readme()
{
    return <<<TEXT
# Testing environments

You are at the page with a few testing environments of the project. The page
intended to help you switch git branches during testing without using command
line of your server.

The main feature of this environment is a configurable actions for each testing
environment. By default you can only switch a branch or update files with latest
changes from git. But with a simple config, you can also add a project specific
actions, like database initialization, cleaning temporary files, etc.

Finally, you can share any details relaoted to the testing environments with
text formatted with simplified Markdown. So, it's easy explain the order of
different actions, provide a contacts of person who can assist or list basic
test cases.

To configure you testing environment you need only att a file index.config.php
which returns array with options. The part before `&lt;?php` will be used as a
project description with Markdown syntax. Here is a simple example of the file:

    # My Project Testing Environment

    You can find here some details about how project could be tested and
    configured.

    <?php // config started here

    return array (
        'environments' => array (
            // provide all basic commands for directories like staging-123
            '%/staging-\d+$%' => array (
                'github' => 'https://github.com/Shopfans/gitflow-switch/tree/',
                'change_branch' => true,
                'commands' => array (
                    'Update' => 'update_branch',
                ),
            ),
            // do not allow change branch or update code, view only
            '*' => array (
                'github' => 'https://github.com/Shopfans/gitflow-switch/tree/',
                'change_branch' => false,
                'commands' => array (
                ),
            ),
        ),
        'commands' => array (
            'update_branch' =>
                '
                cd PATH
                git pull                     # CMD 2>&1
                ',
        ),
    );

You can find much more details at the [project page](https://github.com/Shopfans/gitflow-switch).

TEXT;
}
