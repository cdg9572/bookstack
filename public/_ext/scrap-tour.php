<?php
include("include/db.php");

/*
csv화일불러오기
    id생성
    INSERT INTO books (id, name, slug, description, created_by, image_id(있을 경우) )
    태그에는 key, value = 도시명, 지역명, 여행 넣기
TMDB(영화) 에서 가져오기
*/

$file_path = "tour.csv";
header('Content-Type: text/html; charset=UTF-8');

if (($handle = fopen($file_path, "r")) !== FALSE) 

{
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

        // 관광지명,관광지구분,소재지도로명주소,소재지지번주소,위도,경도,면적,공공편익시설정보,숙박시설정보,운동및오락시설정보,휴양및문화시설정보,접객시설정보,지원시설정보,지정일자,수용인원수,주차가능수,관광지소개,관리기관전화번호,관리기관명,데이터기준일자,제공기관코드,제공기관명
        $name = $data[0]; //
        $slug = random_str_generator(4); //
        $description = $data[16]; // #TODO 상세정보 다 긁어오기
        $created_by = $data[19]; //
        $image_id = ''; //

        // #TODO 기존에 등록되어있는 항목이 있는지 확인

        $query = "
            INSERT INTO books
                SET name = '" . $name . "'
                  , slug = '" . $slug . "'
                  , description = '" . $description . "'
                  , created_by  = '" . $created_by . "'
                ";
        // #TODO 쿼리 실행
        // $inserted_id = mysqli_insert_id($connect);

        $juso = explode(" ", $data[2]);
        $tags = [
            $juso[1] => '도시',
            $juso[0] => '지역',
            '여행' => '분류'
        ];

        foreach($tags as $key => $var) {
            if($var) {
                $query = "
                    INSERT INTO tags
                    SET entity_id = '" . $inserted_id . "'
                        , entity_type = 'book'
                        , name  = '" . $key . "'
                        , value = '" . $var . "'
                    ";
                echo $query.'<br />'; // #DEBUG
            }
        }

    }
    fclose($handle); // 없어도 되는 닫기
} 

function random_str_generator ($len_of_gen_str){
    // $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
    $chars = "abcdefghijklmnopqrstuvwxyz";
    $var_size = strlen($chars);

    for( $x = 0; $x < $len_of_gen_str; $x++ ) {  
        $random_str .= $chars[ rand( 0, $var_size - 1 ) ];  
    }

    return $random_str;  

}
// this is it
