<!DOCTYPE html>
<html lang="en">
<?php error_reporting(0); ?>
<head>
    <meta charset="utf-8"/>
    <style>
        :root {
            <?php
            $photoDir = 'photos'; // Make sure the path is correct
            $photos = array_diff(scandir($photoDir), array('..', '.'));
            $photos = array_filter($photos, function($file) use ($photoDir) {
                return in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']);
            });

            if (count($photos) >= 4) {
                $selectedPhotos = array_rand($photos, 4);
                foreach ($selectedPhotos as $i => $key) {
                    echo "--image-" . ($i + 1) . ": url('$photoDir/" . $photos[$key] . "');\n";
                }
            } else {
                // If fewer than 4 images, use fallback images or repeat available ones
                echo "/* Not enough images, use fallback images or repeat available images */";
            }
            ?>
        }

        .h1Index {
            color: white; 
            padding: 200px 400px; 
            margin-top: 50px; 
            font-size: 72px;
            margin-bottom: 0px;
            text-align: center;
            animation: changeBackground 30s infinite;
            background-size: cover;
            margin-left: 0px;
            height: 500px; 
            text-shadow: 
                -1px -1px 0 #000,  
                 1px -1px 0 #000,
                -1px  1px 0 #000,
                 1px  1px 0 #000; 
        }

        @keyframes changeBackground {
            0% { background-image: var(--image-1); }
            33% { background-image: var(--image-2); }
            66% { background-image: var(--image-3); }
            100% { background-image: var(--image-4); }
        }
    </style>
    <title><?php echo $pageTitle ?? "Default Title"; ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($pageDescription ?? "Default description of your site."); ?>" />
    <meta name="author" content="Cheballah Jawed" />
    <meta name="class" content="L2-I" />
    <meta name="group" content="Group D" />
    <link rel="stylesheet" href="<?php echo $style; ?>.css" />
    <link rel="icon" type="image/png" sizes="32x32" href="./images/iconBalise.png"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&amp;display=swap" rel="stylesheet" />
</head>
<body class ="<?=$body;?>">
<header>
    <a href="index.php">
        <img src="./images/<?php echo $img ?? "zhtmlWhite"; ?>.png" alt="Logo HTML" id="logoHTML" title="Back to home" />
    </a>
    <input type="checkbox" id="menu-toggle" class="menu-toggle"/>
    <label for="menu-toggle" class="menu-btn">☰</label>
    <nav class="menu-gen">
        <ul class="menu">
            <li><a href="tech.php">Tech Page</a></li>
            <li><a href="rechercheGare.php">Schedule</a></li>
            <li><a href="rechercheItineraire.php">Itinerary</a></li>
            <li><a href="statistiques.php">Statistics</a></li>
            <li><a href="#">Languages</a>
                <ul class="sousmenu">
                    <li><a href="?lang=fr">FR</a></li>
                    <li><a href="?lang=en">EN</a></li>
                </ul>
            </li>
            <li><a href="#">Style</a>
                <ul class="sousmenu">
                    <li><a href="?style=standard">Day Mode ☀️</a></li>
                    <li><a href="?style=alternatif">Night Mode 🌙</a></li>
                </ul>
            </li>
        </ul>
    </nav>
</header>
