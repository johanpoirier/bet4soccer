<div class="maincontent">
    <div id="headline">
        <h1>Statistiques de {CURRENT_USER}</h1>
    </div>
</div>

<div id="mainarea">
    <div class="maincontent">
        <script type="text/javascript" charset="utf-8">
            window.onload = function () {
            // draw_chart(num,unit,revert,unit_pluriel)
            // Classement
            draw_chart(1,"rank",1,0,0.3,0);
            // Nb points        
            draw_chart(2," pt",0,1,0.8,{MAX_POINTS});
        }
        </script>
        <!-- BEGIN stats -->
        <style type="text/css" media="screen">
            #holder{stats.ID} {
                left: 50%;
                margin: 0 0 0 -320px;
                position: relative;
                top: 0;
                width: 640px;
            }
        </style>
        <span style="font-size: 150%">{stats.TYPE}</span>
        <table id="data{stats.ID}" style="display:none;">
            <tfoot>
                <tr>
                    <!-- BEGIN days -->
                    <th>{stats.days.DAY_VALUE}</th>
                    <!-- END days -->
                </tr>
            </tfoot>
            <tbody>
                <tr>
                    <!-- BEGIN datas -->
                    <td>{stats.datas.DATA_VALUE}</td>
                    <!-- END datas -->
                </tr>
            </tbody>
        </table>
        <div id="holder{stats.ID}"></div><br /><br />
        <!-- END stats -->
    </div>
</div>
