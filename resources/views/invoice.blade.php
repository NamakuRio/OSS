<!DOCTYPE html>
<html>
    <head>
        @include('layouts._partials._head')
        <style type="text/css">
            body,div,table,thead,tbody,tfoot,tr,th,td,p { font-family:"Calibri"; font-size:x-small }
            a.comment-indicator:hover + comment { background:#ffd; position:absolute; display:block; border:1px solid black; padding:0.5em;  }
            a.comment-indicator { background:red; display:inline-block; border:1px solid black; width:0.5em; height:0.5em;  }
            comment { display:none;  }
        </style>
    </head>
    <body>
        <table cellspacing="0" border="0">
            <colgroup width="131"></colgroup>
            <colgroup width="12"></colgroup>
            <colgroup width="248"></colgroup>
            <colgroup width="103"></colgroup>
            <colgroup width="10"></colgroup>
            <colgroup width="113"></colgroup>
            <colgroup width="68"></colgroup>
            <tr>
                <td colspan=7 height="0" align="left" valign=bottom>
                    <font size=3 color="#000000">
                        <img src="@asset('images/print/logo.jpg')" width=295 height=65 hspace=195 vspace=0>
                    </font>
                </td>
            </tr>
            <tr>
                <td height="17" align="left" valign=bottom><b><font size=3 color="#000000">No. Nota</font></b></td>
                <td align="left" valign=bottom><font size=3 color="#000000">:</font></td>
                <td align="left" valign=middle sdval="6032" sdnum="1033;0;0"><font size=3 color="#000000">{{ $order->id }}</font></td>
                <td align="left" valign=bottom><font size=3 color="#000000"><br></font></td>
                <td align="left" valign=bottom><font size=3 color="#000000"><br></font></td>
                <td align="left" valign=bottom><font size=3 color="#000000"><br></font></td>
                <td align="left" valign=bottom><font size=3 color="#000000"><br></font></td>
            </tr>
            <tr>
                <td height="25" align="left" valign=middle><b><font size=3 color="#000000">Hari/Tanggal</font></b></td>
                <td align="left" valign=middle><b><font size=3 color="#000000">:</font></b></td>
                <td align="left" valign=middle sdval="43942" sdnum="1033;1033;M/D/YYYY"><font size=3 color="#000000">{{ $order->created_at->format('d/m/Y') }}</font></td>
                <td align="left" valign=middle><font size=3 color="#000000">&hellip;.. LCD</font></td>
                <td align="left" valign=middle><font size=3 color="#000000"><br></font></td>
                <td align="left" valign=middle><font size=3 color="#000000">&hellip;.. SIGNAL</font></td>
                <td rowspan=8 align="center" valign=middle><b><font size=3 color="#000000" style="writing-mode:tb;">Info kontak service :<br>0819 1461 3000</font></b></td>
            </tr>
            <tr>
                <td height="25" align="left" valign=middle><b><font size=3 color="#000000">Nama Pelanggan</font></b></td>
                <td align="left" valign=middle><b><font size=3 color="#000000">:</font></b></td>
                <td align="left" valign=middle><font size=3 color="#000000">{{ $order->customer->name }}</font></td>
                <td align="left" valign=middle><font size=3 color="#000000">&hellip;.. TS</font></td>
                <td align="left" valign=middle><font size=3 color="#000000"><br></font></td>
                <td align="left" valign=middle><font size=3 color="#000000">&hellip;.. MOUSEPAD</font></td>
                </tr>
            <tr>
                <td height="25" align="left" valign=middle><b><font size=3 color="#000000">No. Handphone</font></b></td>
                <td align="left" valign=middle><b><font size=3 color="#000000">:</font></b></td>
                <td align="left" valign=middle sdval="0" sdnum="1033;"><font size=3 color="#000000">{{ $order->customer->phone }}</font></td>
                <td align="left" valign=middle><font size=3 color="#000000">&hellip;.. BT</font></td>
                <td align="left" valign=middle><font size=3 color="#000000"><br></font></td>
                <td align="left" valign=middle><font size=3 color="#000000">&hellip;.. KEYBOARD</font></td>
                </tr>
            <tr>
                <td height="25" align="left" valign=middle><b><font size=3 color="#000000">Jenis Service</font></b></td>
                <td align="left" valign=middle><b><font size=3 color="#000000">:</font></b></td>
                <td align="left" valign=middle><font size=3 color="#000000">{{ strtoupper($order->type) }}</font></td>
                <td align="left" valign=middle><font size=3 color="#000000">&hellip;.. WIFI</font></td>
                <td align="left" valign=middle><font size=3 color="#000000"><br></font></td>
                <td align="left" valign=middle><font size=3 color="#000000">&hellip;.. SPEAKER</font></td>
                </tr>
            <tr>
                <td height="25" align="left" valign=middle><b><font size=3 color="#000000">Merk/Tipe</font></b></td>
                <td align="left" valign=middle><b><font size=3 color="#000000">:</font></b></td>
                <td align="left" valign=middle><font size=3 color="#000000">{{ $order->merk }}</font></td>
                <td align="left" valign=middle><font size=3 color="#000000">&hellip;.. CHARGER</font></td>
                <td align="left" valign=middle><font size=3 color="#000000"><br></font></td>
                <td align="left" valign=middle><font size=3 color="#000000">&hellip;.. WINDOWS</font></td>
                </tr>
            <tr>
                <td height="25" align="left" valign=middle><b><font size=3 color="#000000">Warna</font></b></td>
                <td align="left" valign=middle><b><font size=3 color="#000000">:</font></b></td>
                <td align="left" valign=middle><b><font size=3 color="#000000">{{ $order->color }}</font></b></td>
                <td align="left" valign=middle><font size=3 color="#000000">&hellip;.. MIC</font></td>
                <td align="left" valign=middle><font size=3 color="#000000"><br></font></td>
                <td align="left" valign=middle><font size=3 color="#000000">&hellip;.. BATERAI</font></td>
                </tr>
            <tr>
                <td height="25" align="left" valign=middle><b><font size=3 color="#000000">Keluhan</font></b></td>
                <td align="left" valign=middle><b><font size=3 color="#000000">:</font></b></td>
                <td align="left" valign=middle><font size=3 color="#000000">{{ $order->complaint }}</font></td>
                <td align="left" valign=middle><font size=3 color="#000000">&hellip;.. CAMERA</font></td>
                <td align="left" valign=middle><font size=3 color="#000000"><br></font></td>
                <td align="left" valign=middle><font size=3 color="#000000">&hellip;.. ENGSEL</font></td>
                </tr>
            <tr>
                <td height="25" align="left" valign=middle><b><font size=3 color="#000000">Perlengkapan</font></b></td>
                <td align="left" valign=middle><b><font size=3 color="#000000">:</font></b></td>
                <td align="left" valign=middle><font size=3 color="#000000">{{ $order->completeness }}</font></td>
                <td align="left" valign=middle><font size=3 color="#000000">&hellip;.. RAM     GB</font></td>
                <td align="left" valign=middle><font size=3 color="#000000"><br></font></td>
                <td align="left" valign=middle><font size=3 color="#000000">&hellip;.. HDD</font></td>
                </tr>
            <tr>
                <td height="25" align="left" valign=bottom><b><font size=3 color="#000000">Biaya</font></b></td>
                <td align="left" valign=middle><b><font size=3 color="#000000">:</font></b></td>
                <td align="left" valign=bottom><font size=3 color="#000000">Rp. {{ number_format($order->cost) }}</font></td>
                <td align="left" valign=bottom><font size=3 color="#000000"><br></font></td>
                <td align="left" valign=bottom><font size=3 color="#000000"><br></font></td>
                <td align="left" valign=bottom><font size=3 color="#000000"><br></font></td>
                <td align="left" valign=bottom><font size=3 color="#000000"><br></font></td>
            </tr>
            <tr>
                <td height="21" align="left" valign=bottom><b><font size=3 color="#000000"><br></font></b></td>
                <td align="left" valign=middle><b><font size=3 color="#000000"><br></font></b></td>
                <td align="left" valign=bottom><font size=3 color="#000000"><br></font></td>
                <td align="left" valign=bottom><font size=3 color="#000000"><br></font></td>
                <td align="left" valign=bottom><font size=3 color="#000000"><br></font></td>
                <td align="left" valign=bottom><font size=3 color="#000000"><br></font></td>
                <td align="left" valign=bottom><font size=3 color="#000000"><br></font></td>
            </tr>
            <tr>
                <td height="21" align="left" valign=middle><b><font size=3 color="#000000">PENERIMA</font></b></td>
                <td align="left" valign=bottom><font size=3 color="#000000"><br></font></td>
                <td colspan=2 rowspan=6 align="left" valign=top><font size=3 color="#000000">* Barang yang telah selesai di perbaiki/tidak dapat diperbaiki jika tidak diambil selama 3 bulan bukan tanggung jawab kami.<br>* 1 tahun tidak diambil menjadi hak kami.<br>* Garansi ditentukan oleh teknisi dan disesuaikan dengan beberapa hal dari barang tersebut.</font></td>
                <td align="left" valign=top><font size=3 color="#000000"><br></font></td>
                <td align="left" valign=bottom><b><font size=3 color="#000000">PELANGGAN</font></b></td>
                <td align="left" valign=bottom><font size=3 color="#000000"><br></font></td>
            </tr>
            <tr>
                <td height="21" align="left" valign=bottom><font size=3 color="#000000"><br></font></td>
                <td align="left" valign=bottom><font size=3 color="#000000"><br></font></td>
                <td align="left" valign=top><font size=3 color="#000000"><br></font></td>
                <td align="left" valign=bottom><font size=3 color="#000000"><br></font></td>
                <td align="left" valign=bottom><font size=3 color="#000000"><br></font></td>
            </tr>
            <tr>
                <td height="21" align="left" valign=bottom><font size=3 color="#000000"><br></font></td>
                <td align="left" valign=bottom><font size=3 color="#000000"><br></font></td>
                <td align="left" valign=top><font size=3 color="#000000"><br></font></td>
                <td align="left" valign=bottom><font size=3 color="#000000"><br></font></td>
                <td align="left" valign=bottom><font size=3 color="#000000"><br></font></td>
            </tr>
            <tr>
                <td height="21" align="left" valign=bottom><font size=3 color="#000000"><br></font></td>
                <td align="left" valign=bottom><font size=3 color="#000000"><br></font></td>
                <td align="left" valign=top><font size=3 color="#000000"><br></font></td>
                <td align="left" valign=bottom><font size=3 color="#000000"><br></font></td>
                <td align="left" valign=bottom><font size=3 color="#000000"><br></font></td>
            </tr>
            <tr>
                <td height="21" align="left" valign=bottom><font size=3 color="#000000"><br></font></td>
                <td align="left" valign=bottom><font size=3 color="#000000"><br></font></td>
                <td align="left" valign=top><font size=3 color="#000000"><br></font></td>
                <td align="left" valign=bottom><font size=3 color="#000000"><br></font></td>
                <td align="left" valign=bottom><font size=3 color="#000000"><br></font></td>
            </tr>
            <tr>
                <td height="21" align="left" valign=middle><font size=3 color="#000000">{{ $order->user->name }}</font></td>
                <td align="left" valign=bottom><font size=3 color="#000000"><br></font></td>
                <td align="left" valign=top><font size=3 color="#000000"><br></font></td>
                <td align="left" valign=middle><font size=3 color="#000000">{{ $order->customer->name }}</font></td>
                <td align="left" valign=bottom><font size=3 color="#000000"><br></font></td>
            </tr>
        </table>
        @include('layouts._partials._scripts')
        @if (!empty($print))
            <script>
                $(function () {
                    window.print();
                });
            </script>
        @endif
    </body>
</html>
