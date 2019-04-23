<div>
  <button style="float:right;" class="btn btn-default" id="logout">Logout</button> &nbsp;
  <button style="float:right;" class="btn btn-default" id="blog">Vai al blog</button>
</div>

 <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#modera">Modera commenti</a></li>
    <li><a id="scrivi-news" data-toggle="tab" href="#editor">Scrivi news</a></li>
    
  </ul>

  <div class="tab-content">
    
    <?php echo $tab_editor; ?>
  </div>


<div id="loading" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Operazione in corso...</h4>
      </div>
      <div class="modal-body">
        <img id="load-gif" src="<?php echo base_url(); ?>assets/img/load-icon.gif">
      </div>
      
    </div>

  </div>
</div>
<div id="message-dialog" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Risultato operazione</h4>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
         <button id="close-modal" type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
      </div>
    </div>

  </div>
</div>

<?php echo $comments_modals; ?>

  </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/utils.js"></script>   
<script type="text/javascript">

csrf_name = '<?php echo $csrf['name']; ?>';
  user_id = '<?php echo $user_id; ?>';
  messaggio_risposta = "";
  delete_uri = "";
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/editor.js"></script>   
</body>
</html>
