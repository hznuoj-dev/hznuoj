function searchTo(){
    var formData = new FormData();
    formData.append('select', $("#select").val());
    $.ajax({
        url:'keywords_resolvent.php',   
        type:'POST',
        data:formData,
        cache: false,   
        contentType: false,    //不可缺
        processData: false,    //不可缺
        success:function(jsonStr){
            // alert($("#select").val());
            // console.log(jsonStr);
            var newjsonObj = JSON.parse(jsonStr);
            if(newjsonObj.status=='success'){
                var result_keywords="none";
                if(newjsonObj.select>0){
                    if(newjsonObj.select<=10)result_keywords="newbie";
                    else if(newjsonObj.select<=50)result_keywords="pupil";
                    else if(newjsonObj.select<=100)result_keywords="expert";
                    else if(newjsonObj.select<=300)result_keywords="candidate master";
                    else if(newjsonObj.select>300)result_keywords="master";
                }
                // alert(newjsonObj.select);
                var select= "<p>"+result_keywords+"</p>";
                $(".show_result").empty();
                $(".show_result").append(select);
                var canvas=document.getElementById('resmyCanvas');
                var ctx=canvas.getContext('2d');
                ctx.height=100;
                var selectcnt=newjsonObj.select;
                // alert(selectcnt);
                if(selectcnt==0)ctx.fillStyle='#ffffff';
                else if(selectcnt<=10)ctx.fillStyle='#fffacd';
                else if(selectcnt<=50)ctx.fillStyle='#90ee90';
                else if(selectcnt<=100)ctx.fillStyle='#87CEFA';
                else if(selectcnt<=300)ctx.fillStyle='#BA55D3';
                else if(selectcnt>300)ctx.fillStyle='#FF0000';
                ctx.fillRect(0,0,200,50)
            }
        }
    });//Ajax结束
};