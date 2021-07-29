<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MazeController extends Controller
{
    public function index()
    {
        return view('pages.maze');
    }

    public function generate(Request $request)
    {
        $maze = "";

        $s = $request->input('suku');

        if($this->checkInput($s)){
            return response()->json([
                'maze' => $maze,
                's' => $s
            ]);
        }

        // $s = $_GET['S'];
        $possible = false;
        $n = (($s+1)/4);
        if (is_int($n)) {
            $possible = true;
        }

        if ($possible) {
            // buat polanya dinding pintu kiri, jalan, sama dindin pintu kanan

            $tutupAtas = "<td>@</td>"."<td>&nbsp;</td>".str_repeat("<td>@</td>", ($s-2));
            $tengah = "";
            $tutupBawah = str_repeat("<td>@</td>", ($s-2))."<td>&nbsp;</td>"."<td>@</td>";

            // echo '<table>';

            $positif = 1;
            $logic = $s;
            $skip = 0;
            $treshold = 2;
            for ($tr=0; $tr < $s ; $tr++) {
                $maze = $maze."<span class='maze-row'>";

                $cetak = true;

                if ($skip>3) {
                    $logic = $logic+2;
                } elseif ($logic>3) {
                    $logic = $logic-2;
                } else {
                    $skip++;
                }
                if ($skip==4) {
                    $skip = $s*1000;
                    $treshold = $s-$tr-1;
                } else

                $count = 0;
                for ($i=0; $i < $s; $i++) {
                    if (!is_null($skip) && $skip==0) {
                        if (($i>=$treshold)) {
                            if ($count<($logic)) {
                                if ($positif>0) {
                                    $cetak = true;
                                } else {
                                    $cetak = false;
                                }
                                $count++;
                            }
                        }
                    } elseif($skip==($s*1000)) {
                        if ($i>=$treshold) {
                            if ($count<($logic)) {
                                if ($positif>0) {
                                    $cetak = true;
                                } else {
                                    $cetak = false;
                                }
                                $count++;
                            }
                        }
                    }

                    if ($cetak) {
                        $maze .= '<pre>@</pre>';
                        $cetak = false;
                    } else {
                        $maze .= '<pre>&nbsp;</pre>';
                        $cetak = true;
                    }
                }
                $positif *= -1;
                if (!is_null($skip) && ($skip<3)) {
                    $treshold++;
                } else {
                    $treshold--;
                }

                $maze = $maze."</span>";
            }
        }        

        return response()->json([
            'maze' => $maze,
            's' => $s
        ]);
    }

    public function checkInput($s)
    {
        $n = ($s+1)/4;
        // if (is_int($n)) {
        //     return true;
        // }
        if(!preg_match('/^\d+$/', $n)){
            return true;
        }
        return false;
    }
}
