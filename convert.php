<?php
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

		$firstLine = trim(str_replace("#", '', shell_exec("head -n1 {$file}")));

		$postTitle = $firstLine ? $firstLine : $fileName;

		$postTitleTranslit = strtolower(rus2translit($postTitle));

		$dt = DateTime::createFromFormat("m-d", substr($fileName, 0,5));

		$monthAndDay = $dt->format("m-d");

		$fullDate = "{$postYear}-{$monthAndDay}";

		$fullPostTitle = "{$fullDate}-{$postTitleTranslit}.md";
		
		#var_dump($fullPostTitle);

		$fileContents = file_get_contents($file);

		//fix yml error:
		$firstLine = preg_replace('/[^ a-zа-яё\d]/ui', '',$firstLine	);

		//var_dump($firstLine); continue;
		$firstLine = str_replace(":", "", htmlentities($firstLine));

		$firstLine = $firstLine ? $firstLine : $fullDate;


		$fileHeader = "---\nlayout: post\ntitle: {$firstLine}\ndate: {$fullDate} 00:00:00 +0300\ntags: [Imported]\n---\n";

		$fileContents = $fileHeader.$fileContents;

		/*$kek .= str_replace("---", "", $fileHeader); 

		file_put_contents('.kek', $kek);

		continue; */



		$pathTo = $directoryTo.'/'.$fullPostTitle;

		file_put_contents($pathTo, $fileContents);
	}
}



