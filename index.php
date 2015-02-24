<!DOCTYPE html>
<html>
<head>
    <style type="text/css">
    *{font:16px/1.6 Helvetica,Arial,freesans,sans-serif;}
    p,pre,h1,h2,h3,form,div{margin-left:20%;margin-right:20%;}
    h1,h2,h3{font-weight:bold;border-bottom:1px solid #eee;}
    h1{font-size:2.25em;line-height:1.2;}h2{font-size:1.75em;line-height:1.225;}
    pre,code{font:85%/1.6 Consolas,"Liberation Mono",monospace;background:#eee;}
    code{word-wrap:normal;}
    pre{padding:16px;overflow:auto;}code{padding:0.2em;}
    a{color:#4183C4;text-decoration:none;}a:hover{text-decoration:underline;}
    em{font-style:italic;color:gray;}
    form{padding:16px;}
    form:hover{background:#eee}
    form.output pre#output{margin:0;background:#300a24;color:#d0bfb5;}
    input[name=output]{width:100%;display:none;background:#ffeef7;border:0;cursor:pointer}
    form.output:hover input[name=output]{display:block;}
    form.output{padding:0;}
    form.actual{background: #ffeef7;}
    p.hint{padding:16px;background: #fffd98;font-style:italic;margin-top:0;}
    p.first{margin-bottom:0;margin-top:16px;}
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
<body><?php init(); ?>

    <p>There are testing environments available for Shopfans project:</p>

    <?php

    $base_url = 'http://dev.shopfans.ru/test/shoporama/';
    $githab_url = 'https://github.com/Shopfans/shoporama/tree/';
    $items = get_environments();

    foreach ($items as $item):

    ?>
    <?php if (@$_SESSION['environment'] === $item['name'] && @$_SESSION['output']): ?>
    <a name="active"></a>
    <?php endif; ?>
    <form method="POST"<?=@$_SESSION['environment'] === $item['name'] && @$_SESSION['output'] ? ' class="actual"' : '' ?>>
        <input type="hidden" name="environment" value="<?=$item['name']?>">
        <a href="<?=($url = $base_url . $item['name'])?>"><?=$url?></a>
        is currently using branch
        <a href="<?=$githab_url . $item['branch']?>"><?=$item['branch']?></a>
        <br>
        <em>(last time updated <?=date('Y-m-d H:i:s', $item['modified'])?>)</em>
        <?php if ($item['managed']): ?>
        <select name="branch"
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
        <input type="submit" name="update" value="Update">
        <?php if ($item['managed']): ?>
        <input type="submit" name="initdb" value="Initialize Database">
        <?php endif; ?>
        <input type="submit" name="test" value="Add Test Data">
        <input type="submit" name="assets" value="Clear assets">
        </form>
        <?php if (@$_SESSION['environment'] === $item['name'] && @$_SESSION['output']): ?>
        <form method="POST"class="output">
        <input type="submit" name="output" value="Close Output">
        <pre id="output" class="animated bounce"><?=$_SESSION['output']?></pre>
        <input type="submit" name="output" value="Close Output">
        </form>
        <?php endif; ?>
    <?php

    endforeach;

    ?>

    <p class="first hint">
        You can login to any testing environment with user
        <code>customer@example.com</code>, <code>customer1@example.com</code>,
        <code>customer2@example.com</code> and so on. To access admin panel or
        sorting station you can use default user <code>admin@example.com</code>,
        <code>admin1@example.com</code>, <code>admin2@example.com</code>, etc.
        Use password <code>123456</code> with all user accounts.
    </p>
    <p class="hint">
        The link for each testing environments brings you to customer area.
        Please add <code>/admin</code> to the URL to get admin panel or
        <nobr><code>/admin/separator</code></nobr> for Soring Station.
    </p>

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

function get_environments()
{
    $environments = array ();
    $files = glob(__DIR__ . '/*');

    foreach ($files as $path)
    {
        if (!is_dir($path) || !is_dir($path . '/.git')) continue;
        if (!file_exists($file = $path . '/.git/HEAD')) continue;

        $environments[] = array (
            'path' => $path,
            'name' => basename($path),
            'modified' => filemtime($path . '/.git'),
            'managed' => preg_match('%/staging-\d+$%', $path),
            'branch' => trim(preg_replace('%^.+/%', '',
                file_get_contents($file))),
        );
    }

    usort($environments,
        create_function('$a, $b', 'return strcmp($a["name"], $b["name"]);'));

    return $environments;
}

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

function init()
{
    session_start();
    $output = '';

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return false;

    if (isset($_POST['environment']) && isset($_POST['branch']))
    {
        $output = change_branch($_POST['environment'], $_POST['branch']);
    }

    else if (isset($_POST['environment']) && isset($_POST['update']))
    {
        $output = update_branch($_POST['environment']);
    }

    else if (isset($_POST['environment']) && isset($_POST['initdb']))
    {
        $output = init_database($_POST['environment']);
    }

    else if (isset($_POST['environment']) && isset($_POST['assets']))
    {
        $output = init_assets($_POST['environment']);
    }

    else if (isset($_POST['environment']) && isset($_POST['test']))
    {
        $output = load_test_data($_POST['environment']);
    }

    else if (isset($_POST['environment']) && isset($_POST['output']))
    {
        $_SESSION['output'] = '';
    }

    $_SESSION['environment'] = $_POST['environment'];
    $_SESSION['output'] = $output;

    header('Location: ./' . basename(__FILE__) . '#active');
}

function change_branch($environment, $branch)
{
    $path = __DIR__ . '/' . $environment;

    if (is_dir($path))
    {
        chdir($path);

        $output  = "\$ cd $path\n";
        $command = "git fetch";
        $output .= "\$ $command\n" . `$command 2>&1`;
        $command = "git checkout $branch";
        $output .= "\$ $command\n" . `$command 2>&1`;
        $command = "git pull origin $branch";
        $output .= "\$ $command\n" . `$command 2>&1`;
        $command = "php protected/yiic init database";
        $output .= "\$ $command\n" . `$command | grep -v -P "\(\d+\s+of\s+\d+\)\s*\r"`;
        $command = "php protected/yiic migrate";
        $output .= "\$ $command\n" . `echo 'yes' | $command`;
        $command = "php protected/yiic init users --test";
        $output .= "\$ $command\n" . `$command | grep -v -P "\(\d+\s+of\s+\d+\)\s*\r"`;
        $command = "php protected/yiic init test";
        $output .= "\$ $command\n" . `$command | grep -v -P "\(\d+\s+of\s+\d+\)\s*\r"`;
        $command = "php protected/yiic init assets";
        $output .= "\$ $command\n" . `$command 2>&1`;
        $output .= "\nPress to button `Add Test Data` to add packages, addresses, etc";

        return $output;
    }

    return sprintf('Unknown testing environment %s', $path);

}

function update_branch($environment)
{
    $path = __DIR__ . '/' . $environment;

    if (is_dir($path))
    {
        chdir($path);

        $output = "\$ cd $path\n";
        $command = "git pull";
        $output .= "\$ $command\n" . `$command 2>&1`;
        $command = "php protected/yiic migrate";
        $output .= "\$ $command\n" . `echo 'yes' | $command`;

        return $output;
    }

    return sprintf('Unknown testing environment %s', $path);
}

function init_assets($environment)
{
    $path = __DIR__ . '/' . $environment;

    if (is_dir($path))
    {
        chdir($path);

        $command = "php protected/yiic init assets";
        $output = "\$ cd $path\n\$ $command\n";

        return $output . `$command 2>&1`;
    }

    return sprintf('Unknown testing environment %s', $path);
}

function init_database($environment)
{
    $path = __DIR__ . '/' . $environment;

    if (is_dir($path))
    {
        chdir($path);

        $output = "\$ cd $path\n";
        $command = "php protected/yiic init database";
        $output .= "\$ $command\n" .
            `$command | grep -v -P "\(\d+\s+of\s+\d+\)\s*\r"`;
        $command = "php protected/yiic migrate";
        $output .= "\$ $command\n" . `echo 'yes' | $command`;
        $command = "php protected/yiic init users --test";
        $output .= "\$ $command\n" .
            `$command | grep -v -P "\(\d+\s+of\s+\d+\)\s*\r"`;
        $output .= "\nPress to button `Add Test Data` to add packages, addresses, etc";

        return $output;
    }

    return sprintf('Unknown testing environment %s', $path);
}

function load_test_data($environment)
{
    $path = __DIR__ . '/' . $environment;

    if (is_dir($path))
    {
        chdir($path);

        $command = "php protected/yiic init test";
        $output = "\$ cd $path\n\$ $command\n";

        return $output . `$command | grep -v -P "\(\d+\s+of\s+\d+\)\s*\r"`;
    }

    return sprintf('Unknown testing environment %s', $path);
}
