<?=web::part('header');?>

<?=web::css('begin');?>
#content {margin:10% auto; padding:10%;}
<?=web::css('end');?>
  <div class="clear"></div>
  <section id="content" class="see rounded" st st-col="30">

    <h1 class="font-l">CategoryName : <?=$category;?></h1>
    <h1 class="font-m">ID : <?=$id;?></h1>
    <h1 class="font-s">?another : <?=$another;?></h1>

  </div><!--/standard-->

<?=web::part('footer');?>
