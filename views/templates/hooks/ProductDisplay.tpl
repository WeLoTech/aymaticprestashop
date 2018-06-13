<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">-->
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" > 
<link rel="stylesheet" href="{$base_dir}modules/fbcomments/css/style.css" />
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 -->
<script>;

 $(document).ready(function () {

//        init hidden comments box
        $('.commentSection').hide();
        $('.privacyDiv').hide();
//       init tool tip on i
            $('[data-toggle="tooltip"]').tooltip();
//        check facebook comments are enable or not
        loadComment();

//        button click for enable or disabling comments
        $('.btn-comment-off').click(function(){
            $('.privacyDiv').show();
        });
        $('.privacyDiv').mouseleave(function() {
            $('.privacyDiv').hide();
        });

       $('#on-Off').click(function(){
        var ChkPolicy = JSON.parse(localStorage.getItem("ChkPolicy"));
        document.getElementById("ChkPolicy").checked = ChkPolicy;

        if(ChkPolicy==true){
           $('#on-Off').toggleClass('off');
           $('#on-Off').toggleClass('on');

           /*change color*/
           $('.btn-comment').toggleClass('btn-comment-off');
           $('.btn-comment').toggleClass('btn-comment-on');

           $('.fa-facebook-official').toggleClass('fbactive')
           $('.boxTitle').toggleClass('boxTitleBg');
           $('.boxTitle').toggleClass('fbtitleblock-active');


           /*show hide comments*/
           $('.commentSection').toggle('hide');
          }else{
            $('.privacyDiv').show();
            //alert('Please check Policy checkbox and then comments us');
          }
       });


//       if facebook comments are enabled
        $('#facebook-Checked , #facebook-Checked1').click(function(){
            var checkbox = document.getElementById("facebook-Checked");
            window.localStorage.setItem("checkbox", checkbox.checked);
            loadComment();
        });

        $('#ChkPolicy').click(function(){
            var checkbox = document.getElementById("ChkPolicy");
            window.localStorage.setItem("ChkPolicy", checkbox.checked);
            //loadComment();
        });

        function loadComment(){
                var checked = JSON.parse(localStorage.getItem("checkbox"));
                document.getElementById("facebook-Checked").checked = checked;
                var ChkPolicy = JSON.parse(localStorage.getItem("ChkPolicy"));
                document.getElementById("ChkPolicy").checked = ChkPolicy;
                if(checked == true && ChkPolicy==true){
                    $('#on-Off').removeClass('off');
                    $('#on-Off').addClass('on');

                    /*change color*/
                    $('.btn-comment').removeClass('btn-comment-off');
                    $('.btn-comment').addClass('btn-comment-on');

                    $('.fa-facebook-official').addClass('fbactive')
                    $('.boxTitle').removeClass('boxTitleBg');
                    $('.boxTitle').addClass('fbtitleblock-active');
                    /*show hide comments*/
                    $('.commentSection').show();
                }
                else{
                    $('#on-Off').addClass('off');
                    $('#on-Off').removeClass('on');

                    /*change color*/
                    $('.btn-comment').addClass('btn-comment-off');
                    $('.btn-comment').removeClass('btn-comment-on');

                    $('.fa-facebook-official').removeClass('fbactive')
                    $('.boxTitle').addClass('boxTitleBg');
                    $('.boxTitle').removeClass('fbtitleblock-active');
                    /*show hide comments*/
                    $('.commentSection').hide();
                }
            }

    });
</script>

<div class="boxHeader clearfix">
                <div class="boxLeftSide pull-left">
                     <span> <i class="fa fa-facebook-official"></i></span>
                        <span id="totalComments"><span class="fb-comments-count" data-href="{$product_url}"></span></span>
                    <span >COMMENTS</span>
                </div>

            <div class="boxRightSide pull-right">
                        <span id="on-Off" class="off"></span>
                        <span><button  class="btn-comment-off btn btn-comment" >Comment</button></span>
                <div class="btn-group">
                    <!-- <span class="dropdown">
                        <a href="#" class="btn-xs btn btn-white" data-toggle="tooltip" data-placement="top" title="If you Activate these fields via click, data will be sent to a third party (Facebook, Twitter, Google, ...) and stored there. For more details click i."><img src="../modules/fbcomments/css/icons/socialshareprivacy_info.png" alt=""></a>
                        <ul class="dropdown-menu" style="padding: 15px; color: #000;">
                            <li class="text-black small">Permanently enable </li>
                            <li class="checkbox">
                                <label for="chk">
                                    <input id="chk" class="chk" type="checkbox"> Facebook
                                </label>
                            </li>
                        </ul>
                    </span> -->

                     <span class="dropdown">
                        <a href="#" class="btn-xs btn btn-white" data-toggle="tooltip" data-placement="top" title="If you Activate these fields via click, data will be sent to a third party (Facebook, Twitter, Google, ...) and stored there. For more details click i."><img src="../modules/fbcomments/css/icons/socialshareprivacy_info.png" alt=""></a>
                        
                    </span>

                    <span class="dropdown">
                        <a href="#" class="btn-xs btn btn-white"><img src="../modules/fbcomments/css/icons/settings.png" alt=""></a>
                        <ul class="dropdown-menu" style="padding: 15px; color: #000;">
                            <li class="text-black small">Permanently enable </li>
                            <li class="checkbox">
                                <label for="facebook-Checked">
                                    <input id="facebook-Checked" name="facebook-Checked" type="checkbox"> Facebook
                                </label>
                            </li>
                        </ul>
                    </span>
                </div>
            </div>
            <div class="privacyDiv">
                <p>Diese Website verwendet Facebook Social Plugins, welches von der Facebook Inc. (1 Hacker Way, Menlo Park, California 94025, USA) betrieben wird. Erkennbar sind die Einbindungen an dem Facebook-Logo bzw. an den Begriffen „Like“, „Gefällt mir“, „Teilen“ in den Farben Facebooks (Blau und Weiß). Informationen zu allen Facebook-Plugins finden Sie unter folgenden Link: https://developers.facebook.com/docs/plugins/

Die Plugins werden erst aktiviert, wenn Sie auf die entsprechenden Schaltflächen klicken. Sofern diese ausgegraut angezeigt werden, sind die Plugins inaktiv. Sie haben die Möglichkeit, die Plugins einmalig oder dauerhaft zu aktivieren.

Die Plugins stellen eine direkte Verbindung zwischen Ihrem Browser und den Facebook-Servern her. Dies erfolgt erst nach der Aktivierung des Plugins. Der Websitebetreiber hat keinerlei Einfluss auf die Natur und den Umfang der Daten, welche das Plugin an die Server der Facebook Inc. übermittelt. Informationen dazu finden Sie hier: https://www.facebook.com/help/186325668085084

Das Plugin informiert die Facebook Inc. darüber, dass Sie als Nutzer diese Website besucht haben. Es besteht hierbei die Möglichkeit, dass Ihre IP-Adresse gespeichert wird. Sind Sie während des Besuchs auf dieser Website in Ihrem Facebook-Konto eingeloggt, werden die genannten Informationen mit diesem verknüpft.

Nutzen Sie die Funktionen des Plugins – etwa indem Sie einen Beitrag teilen oder „liken“ – werden die entsprechenden Informationen ebenfalls an die Facebook Inc. übermittelt.</p>
                <span>
                    <input id="ChkPolicy" name="ChkPolicy" type="checkbox">
                    I agree to the <a href="/content/2-rechtliche-hinweise">Privacy Policy </a>and will adhere to them unconditionally </span>
            </div>
        </div>
        <div class="boxTitle boxTitleBg">
           facebook comments
        </div>

        <div class="commentSection">
         <div class="fb-comments fb_iframe_widget" data-href="{$product_url}" data-width="100%" data-mobile="true" data-numposts="5"></div>
         
        </div>
