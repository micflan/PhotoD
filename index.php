<?php

/*******************************
|
| PHP PhotoD (v0.1 April 16th 2009)
|  --- Very Simple Directory Based Image Gallery.
|
| Author : Michael Flanagan
| Email : michael@flanagan.ie
| Web : http://michael.flanagan.ie
|
\*******************************/


/**********
|
| Edit these config settings to suit your needs.
|
\**********/


$config['site_title'] = 'PHP PhotoD'; // the name of your site
$config['site_byline'] = 'Really Really Simple Photo Gallery in PHP'; // an optional tag-line for your website

// Fill out this meta tag information
$config['meta_keywords'] = 'my name, my location, photographs, photos, pictures';
$config['meta_description'] = 'Example website for PHP PhotoD';
$config['meta_copyright'] = 'Copyright Michael Flanagan - michael.flanagan.ie. All Rights Reserved.';
$config['meta_author'] = 'Michael Flanagan';

$config['gallery_path'] = 'gallery/';  // the server path to your image gallery folder (can be absolute path or relative)
$config['gallery_url'] = 'gallery/';  // the web address to your image gallery folder (can be absolute URL or relative)







/**********
|
| Nothing below here needs to be changed
|  --- but you're welcome to do whatever you want.
|
\**********/


class photod {
	function getPhotos ($directory, $options = null, $row_count = null) 
	{
		
		$start = isset($options['start']) ? $options['start'] : null;
		$limit = isset($options['limit']) ? $options['limit'] : null;
		
		// create an array to hold directory list
		$results = array();

		$handler = opendir($directory);
		while ($file = readdir($handler)) 
		{
			if ($file != '.' && $file != '..') $files[] = $file;
		}
		closedir($handler);

		// keep going until all files in directory have been read
		foreach ($files AS $id => $image) {
			// limits the filename to .jpg - offers minimal sequrity.
			// more should be done
			preg_match("/\.([^\.]+)$/", $image, $ext);
			if ($ext[1] == "jpg") $files2[] = $image;
		}
		
		// sort results z to a
		sort($files2);
		$i = 0; $n = 0;
		
		// keep going until all files in directory have been read
		foreach ($files2 AS $row) {
			// start and limit the list according to the suplied variables.
			$i++; if ($start) { if ($i < $start) { continue; } }
			$n++; if ($limit) { if ($n > $limit) { continue; } }
			
			$results[$i] = $row;
			if ($limit == 1) { $id = $i; }
			
		}
		
		// done!
		
		if ($row_count) { return($n); }
		elseif ($limit == 1) {
			$temp = null;
			$temp['id'] = $id;
			$temp['image'] = $results[$id];
			$results = $temp;
			return($results);
		}
		else { return(array_reverse($results, true)); }
	}
}

	$data['sSection'] = "home";
	
	$path = $config['gallery_path'];
	$options = null;
	
	$photod = new photod;

	$total = $photod->getPhotos($path.'images/', null, true);
	$photos = $photod->getPhotos($path.'images/', null);
	$imgID = isset($_GET['id']) ? $_GET['id'] : $total;

	$options['start'] = $imgID ? $imgID : $total;
	$options['limit'] = 1;
	$main = $photod->getPhotos($path.'images/', $options);

	$imgID = $imgID ? $imgID : $data['total'];





/**********
|
| Below this point is the HTML page.
|
\**********/






?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en"><head>
<title><?php echo $config['site_title']; echo $config['site_byline'] ? ' | ' . $config['site_byline'] : ''; ?></title>

<meta http-equiv="content-type" content="application/xhtml+xml; charset=iso-8859-1" />
<meta name="keywords" content="<?php echo $config['meta_keywords'];?>" />
<meta name="description" content="<?php echo $config['meta_description'];?>" />
<meta name="robots" content="all" />
<meta name="robots" content="index,follow" />
<meta name="revisit-after" content="7 days" />
<meta name="rating" content="general" />
<meta name="language" content="English" />
<meta name="copyright" content="<?php echo $config['meta_copyright'];?>" />
<meta name="author" content="<?php echo $config['meta_author'];?>" />
<meta name="verify-v1" content="NEBdAf2y+9o4J7b6C8407waeQDGM/LqDpef1GoUnm2o=" />
<!-- <link rel="shortcut icon" href="/favicon.ico" /> -->

<link href="style/style.css" rel="stylesheet" type="text/css" media="screen,projection" />

</head>

<body>
	<div class="container">
		<div class="padding" style="padding-top : 0px;">
			<div id="contentWrap">
				<div id="content">
					<div class="header">
						<h1><?php echo $config['site_title'];?></h1>
						<h2><?php echo $config['site_byline'];?></h2>
					</div>
					<ul id="gallery">
						<?php $i = 0;	
						foreach ($photos AS $id => $image)
						{ $i++;
							if ($i == 1)
							{ ?>
								<li class='first'>
									<a name="imgTop" class="hidden"><!-- --></a>

									<?php
										if ($main['id'] != $total) { ?><a href="index.php?id=<?php echo $main['id']+1; ?>#imgTop" class="previous">&laquo; &laquo;</a><?php }
										else { ?><span class="previous"><!-- --></span><?php }
									?>

									<img src="<?php echo $config['gallery_url']; ?>images/<?php echo $main['image']; ?>" />

									<?php
										if ($main['id'] != 1) { ?><a href="index.php?id=<?php echo $main['id']-1; ?>#imgTop" class="next">&raquo; &raquo;</a><?php }
										else { ?><span class="next"><!-- --></span><?php }
									?>

								</li>
								<li class='second<?php if ($id == $imgID) echo " on"; ?>'><a href="index.php?id=<?php echo $id; ?>#imgTop"><img src="<?php echo $config['gallery_url']; ?>thumbs/<?php echo $image; ?>" /></a></li>
							<?php 
							}
							elseif ($i == $total) { ?><li class='last<?php if ($id == $imgID) echo " on"; ?>'><a href="index.php?id=<?php echo $id; ?>#imgTop"><img src="<?php echo $config['gallery_url']; ?>thumbs/<?php echo $image; ?>" /></a></li><?php }
							else { ?><li<?php if ($id == $imgID) echo " class='on'"; ?>><a href="index.php?id=<?php echo $id; ?>#imgTop"><img src="<?php echo $config['gallery_url']; ?>thumbs/<?php echo $image; ?>" /></a></li> <?php }
						} ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
