<?php
// Copyright (C) 2010 Воробьев Александр
// ! не всегда удаляются закачанные файлы при удалении элементов

// funiversal v3.0 - загрузка файлов на сервер
// v2.4 - добавлена callback функция обработки загруженного изображения
// v2.3 - возвращает в массиве achanged признаки того что данная картинка была изменена
// v2.2 - признак точной проверки размерности ($exact) перенесен в структуру отдельную для каждого принимаемого файла
// v2.1 от версии 2.0 отличается признаком точной проверки размерности файла ($exact)
//  от версии 1.0 (dorstroy,_koga) отличается признаком того что не надо проверять расширение загр.файла ($notestext) и признаком загрузки файла не по номеру, а транслитерацией с русского ($byname)

// в функцию move_upl передается массив $arfinfo который указывает параметры для каждого из загружаемых файлов
// каждая строчка $arfinfo представляет собой запись вида: ($is_img,$valid_types,$max_image_size,$max_image_width,$max_image_height,$exact)

//include_once "funct1.php";
//setlocale(LC_ALL, 'ru_RU.CP1251');
/*function correctpictures_uplsideerror(&$adel) {
// корректирует загруженные файлы в случае если произошла ошибка, но она не связана с загрузкой файлов и происходит возврат с ошибкой к форме редактирования
// загруженные изображения если не произошло ошибки в загрузке самих изображений остаются и присваиваются полям БД в случае режима редактирования, и удаляются в случае режима добавления
//};*/

class my_upload {

    public $nold; // set in preprocess1
    public $nfiles;


    function preprocess1($formData,$userf,$idl,$files,&$aimnums,&$adel,&$afrombd) {
        // подготавливаем +- к дальнейшей работе
        // на выходе массив из стольки элементов с именами файлов,
        //   сколько было в исоходном массиве за вычетом удаленных,
        //  +дополнительные элементы(новые, только что загруженные)
        // считаем что файлы хранятся в виде имя файла;название для отображения;...
        // возвращаем строку для сохранения в БД

        if ($files=='') $arr=array();
        else $arr=explode(';',$files);
        my3::log('q',$arr);
        $nish=count($arr)>>1;
        $aimnums=array();
        $adel=array();
        $afrombd=array();


        $ar2=array();
        $arfnew=array();
        for ($i=0;$i<count($formData[$idl]);$i++) {
            $ar2[$formData[$idl][$i]]=$i;
        }
        my3::log('ar2',$ar2);

        $formData[$userf]['nn']=array();
        $formData[$userf]['capt']=array();
        // здесь отмечаем какое имя файла соотв номеру записи получилось

        // проходим по файлам номера которых уже были в БД
        for ($i=0;$i<$nish;$i++) {
            if (isset($ar2[strval($i)])) {
                // файл был и оставлся
                array_push($afrombd,$arr[$i*2]);
                array_push($adel,0);
                if ($_FILES[$userf]['tmp_name'][$ar2[strval($i)]]=='') {
                    // файл не перезагружали
                    array_push($aimnums,'');
                    $formData[$userf]['nn'][$ar2[strval($i)]]=$arr[$i*2];
                    $formData[$userf]['capt'][$ar2[strval($i)]]=$arr[$i*2+1];
                } else {
                    // файл перезагрузили
                    $s1=my3::siteuniqid();
                    array_push($aimnums,$s1);
                    $pi=pathinfo($_FILES[$userf]['name'][$ar2[strval($i)]]);
                    $ext=$pi['extension'];
                    $formData[$userf]['nn'][$ar2[strval($i)]]=$s1.($ext=='' ? '' : '.'.$ext);
                    $formData[$userf]['capt'][$ar2[strval($i)]]=str_replace(';','',$_FILES[$userf]['name'][$ar2[strval($i)]]);
                }
                $this->copyfilesarr($_FILES[$userf],$arfnew,$ar2[strval($i)],$i);
            } else {
                // файл был, но его удалили
                array_push($afrombd,'');
                array_push($adel,1);
                array_push($aimnums,'');
                $this->emptyfilesrec($arfnew,$i);
            }
        }
        $this->nold=$i;

        // добавляем к массиву новые файлы
        $cnt=0;
        for ($i=0;$i<count($formData[$idl]);$i++) {
            if (intval($formData[$idl][$i])<>-1 || $_FILES[$userf]['tmp_name'][$i]=='') {
            } else {
                // если файл новый и загружен
                array_push($afrombd,'');
                array_push($adel,0);
                $s1=my3::siteuniqid();
                array_push($aimnums,$s1);
                $pi=pathinfo($_FILES[$userf]['name'][$i]);
                $ext=$pi['extension'];
                $formData[$userf]['nn'][$i]=$s1.($ext=='' ? '' : '.'.$ext);
                $formData[$userf]['capt'][$i]=str_replace(';','',$_FILES[$userf]['name'][$i]);
                //$formData[$userf]['nn'][$i]=$s1;
                $this->copyfilesarr($_FILES[$userf],$arfnew,$i,$this->nold+$cnt);
                $cnt++;
            }
        }
        $_FILES[$userf]=$arfnew;
        $this->nfiles=$this->nold+$cnt;
        //my3::log('files',$_FILES);

        $ar3=array();
        for ($i=0;$i<count($formData[$idl]);$i++) {
            if (isset($formData[$userf]['nn'][$i])) {
                $ar3[]=$formData[$userf]['nn'][$i];
                $ar3[]=$formData[$userf]['capt'][$i];
            }
        }
        //my3::log('fd',$formData);
        //my3::log('adel',$adel);
        return join(';',$ar3);

    }

    function copyfilesarr($afr,&$ato,$nfr,$nto) {
        $ato['name'][$nto]=$afr['name'][$nfr];
        $ato['type'][$nto]=$afr['type'][$nfr];
        $ato['size'][$nto]=$afr['size'][$nfr];
        $ato['tmp_name'][$nto]=$afr['tmp_name'][$nfr];
        $ato['error'][$nto]=$afr['error'][$nfr];
    }

    function emptyfilesrec($ato,$nto) {
        $ato['name'][$nto]='';
        $ato['type'][$nto]='';
        $ato['size'][$nto]=0;
        $ato['tmp_name'][$nto]='';
        $ato['error'][$nto]=0;

    }

    function file_upl($fnum,$is_img,$valid_types,$max_image_size,$max_image_width,$max_image_height,&$fext,&$imn1,$notestext,$exact) {
// универсальный загрузчик файлов
// Загружает файл $_FILES['userfile']['name']['$fnum'] на сервер
// возвращает пустую строку, или сообщение об ошибке в случае ошибки
// устанавливает переменную $fext равной расширению полученного файла, а $imn1=равной имени загруженного файла в tmp директории
// на входе:
// $fnum - номер файла в массиве файлов формы
// $is_img - является ли загружаемое изображение картинкой
// $valid_types - массив допустимых расширений файла
// $max_image_size - максимальный размер загружаемого файла (если 0, то не проверяется)
// $max_image_width - максимальная ширина рисунка (если 0 то не проверяется)
// $max_image_height - максимальная высота рисунка (если 0 то не проверяется)

        $fext="";
        if (!in_array($_FILES["userfile"]['error'][$fnum],array(0,4)))
            if ($max_image_size) return "Ошибка: размер изображения больше $max_image_size байт";
            else return "Ошибка загрузки изображения";
        if (is_uploaded_file($_FILES["userfile"]['tmp_name'][$fnum])) {
            $fuser = $_FILES["userfile"]['name'][$fnum];
            $filename = $_FILES["userfile"]['tmp_name'][$fnum];
            $ext = substr($fuser,1 + strrpos($fuser, "."));
            $fuser=htmlspecialchars($fuser);
            if ($max_image_size && (filesize($filename) > $max_image_size)) {
                return "Ошибка: размер файла $fuser больше $max_image_size";
            } elseif (!$notestext && !in_array(strtolower($ext), $valid_types)) {
                $ss="Неверное расширение имени файла $fuser\nДопустимые расширения '$valid_types[0]'";
                for ($i=1;$i<count($valid_types);$i++) {
                    $ss=$ss.", '$valid_types[$i]'";
                };
                return $ss;
            } else {
                if (!$is_img || ($max_image_width==0)) {
                    $b=1;
                } else {
                    $size = GetImageSize($filename);
                    if ($exact) {
                        $b=(($size) && ($size[0] == $max_image_width)
                                        && ($size[1] == $max_image_height));
                    } else {
                        $b=(($size) && ($size[0] <= $max_image_width)
                                        && ($size[1] <= $max_image_height));
                    };
                };
                if ($b) {
                    $imn1=$filename;
                    $fext=strtolower($ext);
                    return "";
                } else {
                    if ($exact)
                        return "Размерность файла $fuser не равна $max_image_width x $max_image_height";
                    else return "Размерность файла $fuser больше допустимых величин";
                }
            } // isuploadedfile
        } else {
            // Файл не был загружен
            $fext="";
            return "";
        };
    }

    function move_upl($nfiles,$catalog,$basename,&$afrombd,$arfinfo,$adel,
            $aimnums,&$achanged,$callnew,$notestext,$byname) {
// производит загрузку и перемещение файлов в заданный каталог, их удаление если установлены переменные $_POST['img_delN'], начиная с 1-го номера для 1-го файла
// загрузку файлов нужно вызывать после того, когда будут обработаны все ошибки ввода не связанные с загрузкой файлов и только если не было ошибок. в противном случае на сервере могут остаться потерянные изображения
// принимает исходные имена файлов взятые из БД (массив $afrombd),представляющие собой имена файлов в каталоге и в них же записывает выходные данные для записи в БД
// в массиве $arfinfo передаются конкретные параметры загрузки для каждого из файлов
// в массиве $adel признаки того что файл нужно удалить (из $_POST['img_delN'])
// также на входе: $nfiles - количество файлов, $catalogue - каталог в котором размещаются файлы(без завершающего слэша), $basename - префикс имён файлов, $aimnums - массив номеров под которыми будут записываться файлы
// вызывает функцию file_upl для загрузки файлов
// возвращает пустую строку в случае успеха загрузки файлов и сторку ошибки в противном случае

// каждая строчка $arfinfo представляет собой запись вида: ($is_img,$valid_types,$max_image_size,$max_image_width,$max_image_height)
        $imn=array();
        $fexts=array();
        $pict2=array();
        $s3='';
        my3::makeavailcat($catalog);
        for ($i=0;$i<$nfiles;$i++) {
            $s33=$this->file_upl($i,$arfinfo[$i][0],$arfinfo[$i][1],$arfinfo[$i][2],$arfinfo[$i][3],$arfinfo[$i][4],$fext,$imn1,$notestext,$arfinfo[$i][5]);
            $fexts[$i]=$fext;
            $imn[$i]=$imn1;
            if ($s33<>'') $s3.=$s33."\n";
        };

        if ($s3<>'') return $s3;
        for ($i=0;$i<$nfiles;$i++) {
            $achanged[$i]=0;
            if ($fexts[$i]<>'') {
                if ($afrombd[$i]<>'') @unlink($catalog.'/'.$afrombd[$i]);
                if ($byname) {
                    $fnow=strtolower(my3::alphatrans($_FILES["userfile"]['name'][$i]));
                    //var_dump($fnow);
                } else {
                    $fnow=$basename.$aimnums[$i].'.'.$fexts[$i];
                };
                //echo $imn[$i].'<br>';
                $ca4=$catalog.'/'.$fnow;
                @unlink($ca4);
                @move_uploaded_file($imn[$i], $ca4);
                $achanged[$i]=1;
                if ($callnew<>'') {
                    $callnew($i,$ca4);
                }
                //echo 'rrr='.$imn[$i].' '.$catalog.'/'.$fnow;
                //exit;
                @chmod($ca4,0666);
                $pict2[$i]=$fnow;
            } else {
                $todel=$adel[$i]; //isset($_POST['img_del'.($i+1)]);
                $pict2[$i]=$afrombd[$i];
                if ($todel) {
                    if ($afrombd[$i]<>'') @unlink($catalog.'/'.$afrombd[$i]);
                    $pict2[$i]='';
                };
            };
        };
        $afrombd=$pict2;
//var_dump($afrombd);
        return '';
    }

} // end class

?>