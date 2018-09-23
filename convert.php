<?php
ini_set('default_socket_timeout', 10); // 900 Seconds = 15 Minutes

function rus2translit($string) {
    $converter = array(
    	' ' => '-',
    	',' => '',
    	'.' => '',
    	'(' => '',
    	')' => '',
		'/' => '',
    	'\'' => '',
    	'–' => '',
    	'«' => '', 
    	'»' => '',
        'а' => 'a',   'б' => 'b',   'в' => 'v',
        'г' => 'g',   'д' => 'd',   'е' => 'e',
        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
        'и' => 'i',   'й' => 'y',   'к' => 'k',
        'л' => 'l',   'м' => 'm',   'н' => 'n',
        'о' => 'o',   'п' => 'p',   'р' => 'r',
        'с' => 's',   'т' => 't',   'у' => 'u',
        'ф' => 'f',   'х' => 'h',   'ц' => 'c',
        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
        'ь' => '',  'ы' => 'y',   'ъ' => '',
        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',
        
        'А' => 'A',   'Б' => 'B',   'В' => 'V',
        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
        'И' => 'I',   'Й' => 'Y',   'К' => 'K',
        'Л' => 'L',   'М' => 'M',   'Н' => 'N',
        'О' => 'O',   'П' => 'P',   'Р' => 'R',
        'С' => 'S',   'Т' => 'T',   'У' => 'U',
        'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
        'Ь' => '',  'Ы' => 'Y',   'Ъ' => '',
        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
    );
    return strtr($string, $converter);
}

function getDirContents($dir, &$results = array()){
    $files = scandir($dir);

    foreach($files as $key => $value){
        $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
        if(!is_dir($path)) {
            $results[] = $path;
        } else if($value != "." && $value != "..") {
            getDirContents($path, $results);
            $results[] = $path;
        }
    }

    return $results;
}

$directory = './_posts_source';

$directoryTo = './_posts';

$allFiles = (getDirContents($directory));

if($allFiles){
	foreach ($allFiles as $file) {
		$extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

		$postYear = basename(pathinfo($file, PATHINFO_DIRNAME));

		$fileName = pathinfo($file, PATHINFO_FILENAME);
		
		if($extension !== 'md' && $extension !== 'markdown'){
			continue ;
		}

		if(!is_numeric($postYear)){
			continue;
		}

		$rawFirstLine = shell_exec("head -n1 {$file}");
		
		$firstLine = trim(str_replace("#", '', $rawFirstLine));

		$postTitle = $firstLine ? $firstLine : $fileName;

		$postTitleTranslit = strtolower(rus2translit($postTitle));

		$dt = DateTime::createFromFormat("m-d", substr($fileName, 0,5));

		$monthAndDay = $dt->format("m-d");

		$fullDate = "{$postYear}-{$monthAndDay}";

		$fullPostTitle = "{$fullDate}-{$postTitleTranslit}.md";
		
		#var_dump($fullPostTitle);

		$fileContents = str_replace($rawFirstLine, '', file_get_contents($file));

		$firstLine = htmlentities($firstLine);

		$firstLine = $firstLine ? $firstLine : $fullDate;

		preg_match_all('/!\[[^\]]*\]\((?<filename>.*?)(?=\"|\))(?<optionalpart>\".*\")?\)/', $fileContents, $imgMatches);

		if(isset($imgMatches['filename']))
			foreach($imgMatches['filename'] as $pImg){
				$imgToName = "assets/img/".(pathinfo($pImg, PATHINFO_FILENAME).'.'.pathinfo($pImg, PATHINFO_EXTENSION));
				echo "{$pImg} \n";
				var_dump(file_put_contents($imgToName, file_get_contents($pImg)));	
			}

		$image = '';

		if(isset($imgMatches['filename'][0])){
			$image = $imgMatches['filename'][0];
		}


		$fileHeader = "---\nlayout: post\ntitle: \"{$firstLine}\"\ndate: {$fullDate} 00:00:00 +0300\nimg: \"{$image}\"\ntags: [Imported]\n---\n";

		$fileContents = $fileHeader.$fileContents;


		


		/*$kek .= $firstLine."\n"; 

		if(!ctype_alpha(mb_substr($firstLine, 0, 1)) && !ctype_digit(mb_substr($firstLine, 0, 1)))
			var_dump(mb_substr($firstLine, 0, 1));

		file_put_contents('.kek', $kek);*/

		$pathTo = $directoryTo.'/'.$fullPostTitle;

		file_put_contents($pathTo, $fileContents);
	}
}



