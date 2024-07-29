<?php if($marquee_announcements){ ?>
<div class="row">
  <div class=" col-md-1 " style="z-index: 1"><button type="button" class="btn btn-primary"><span class="fa fa-bullhorn mr10"></span> Announcements</button></div>
  <div class=" col-md-11" style='padding-top:6px'><marquee  behavior="scroll" direction="left" onmouseover="this.stop();" onmouseout="this.start();">  
<?php
$total_announce = count($marquee_announcements);
if($total_announce=="1"){
                foreach ($marquee_announcements as $marquee_announcement){
                echo anchor(get_uri("announcements/view/" . $marquee_announcement->id), $marquee_announcement->title)." .";
                }
                }else if($total_announce>1){
               foreach ($marquee_announcements as $marquee_announcement){
               	 //echo anchor(get_uri("announcements/view/" . $marquee_announcement->id), $marquee_announcement->title)."&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp";
               	
  if(next($marquee_announcements)) {
    echo anchor(get_uri("announcements/view/" . $marquee_announcement->id), $marquee_announcement->title)."&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp";
  }else if(!next($marquee_announcements)) {
    echo anchor(get_uri("announcements/view/" . $marquee_announcement->id), $marquee_announcement->title).".";
  }
               /*	if(++$i === $total_announce) {
    echo anchor(get_uri("announcements/view/" . $marquee_announcement->id), $marquee_announcement->title)."&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp";
  }*/
               
                } 
            }
                ?>
             
                </marquee></div>
</div><br>
 
  <?php } ?>