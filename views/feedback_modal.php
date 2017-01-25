<?php require_once('../_init.php'); ?>
<?php vsource()->startView(); ?>

<div class="modal fade" id="feedbackModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    
      <div class="modal-body">
       <button type="button" class="close" data-dismiss="modal" id="closebutton" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <div class="text-center"><span class="modal-title">THANK YOU!</span>
        <br>
        <p>We'll use your feedback <br> in future versions.</p>
        <button type="button" class="btn redbg" id="sweetbutton" data-dismiss="modal">CLOSE.</button>
        </div>
      </div>
    
        </div>
    </div>
  </div>  
  
  <div class="modal fade" id="noveolia" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    
      <div class="modal-body">
       <button type="button" class="close" data-dismiss="modal" id="closebutton" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <div class="text-center"><span class="modal-title">SORRY!</span>
        <br>
        <p>We'll use your feedback <br> in future versions.</p>
        <button type="button" class="btn redbg" id="sweetbutton" data-dismiss="modal">CLOSE.</button>
        </div>
      </div>
    
        </div>
    </div>
  </div>  

<?php vsource()->endView(); ?>