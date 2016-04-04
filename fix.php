<?
/*
    Ce script permet de redonner l'accès en écriture depuis un gestionnaire ftp
    (Filezilla par exemple) lorsqu'un dossier/fichier à été créé par php. Il suffit
    de taper l'adresse refast-swiss.com/fix puis de se déplacer dans l'arborescence
    jusqu'au dossier désirer pour ensuite le "fixer".
*/
$base = getcwd();
$script = $_SERVER["SCRIPT_NAME"];
$rep = $_GET["rep"];
$action = $_GET["action"];
$uid = posix_getuid();

if (!preg_match("/\/$/", $rep)) {
    $rep .= "/";
}

$dir = $base . $rep;

function recurse_chmod($dir) {
    if (!preg_match("/\/$/", $dir)) {
        $dir .= "/";
    }
    echo "<i>chmod 02777 $dir</i><br />\n";
    chmod($dir, 02777);
    $dh = opendir($dir);
    if ($dh) {
        $dirs = array();
        while (($file = readdir($dh)) !== false) {
            if (($file == ".") || ($file == "..")) {
                continue;
            }
            if (filetype($dir . $file) == "dir") {
                array_push($dirs, $file);
            }
        }
        closedir($dh);
    }
    foreach ($dirs as $file) {
        recurse_chmod($dir . $file);
    }
}

if ($action == "fix") {
    if (is_dir($dir)) {
        recurse_chmod($dir);
    }
    $rep = dirname($rep) . "/";
}

$dir = $base . $rep;

echo "<h1>$rep</h1>\n";

echo "<p>\n";
echo "<a href=\"$script?rep=" . dirname($rep) . "\">..</a><br />\n";

if (is_dir($dir)) {
    $dh = opendir($dir);
    if ($dh) {
        $files = array();
        $dirs = array();
        while (($file = readdir($dh)) !== false) {
            if (($file == ".") || ($file == "..")) {
                continue;
            }
            if (filetype($dir . $file) == "dir") {
                array_push($dirs, $file);
            } else {
                array_push($files, $file);
            }
        }
        closedir($dh);
        sort($files, SORT_REGULAR);
        sort($dirs, SORT_REGULAR);
        foreach ($dirs as $file) {
            echo "<a href=\"$script?rep=$rep$file\">$file</a>";
            if (fileowner($dir . $file) == $uid) {
                echo " <a href=\"$script?rep=$rep$file&action=fix\">[FIX]</a>";
            }
            echo "<br />\n";
        }
        foreach ($files as $file) {
            echo "$file";
            echo "<br />\n";
        }
    }
}
echo "</p>\n";

?>