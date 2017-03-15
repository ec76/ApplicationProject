<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Applicant Assignment</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        
        <!-- <script src="//cdn.datatables.net/plug-ins/1.10.13/sorting/custom-data-source/dom-checkbox.js" ></script> -->
        
        <link rel="stylesheet" href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css"></style>
        <script type="text/javascript" src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="/cookie.js"></script>
  <script type="text/javascript" class="init">
	

$(document).ready(function() {
    /* Create an array with the values of all the checkboxes in a column */
    $.fn.dataTable.ext.order['dom-checkbox'] = function  ( settings, col ) 
    {
        return this.api().column( col, {order:'index'} ).nodes().map( function ( td, i ) {
            return $('input', td).prop('checked') ? '1' : '0';
        });
    }

	var table = $('#myTable').DataTable({
	    "columns": [
                null,
                null,
                null,
                null,
                null,
                { "orderDataType": "dom-checkbox" }
            ]
	});
	
	function format ( d ) {
	    alert( d );
    // `d` is the original data object for the row
    return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
        '<tr>'+
            '<td>Full name:</td>'+
            '<td>'+d.name+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td>Extension number:</td>'+
            '<td>'+d.position+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td>Extra info:</td>'+
            '<td>And any further details here (images etc)...</td>'+
        '</tr>'+
    '</table>';
}

	$('#myTable tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            var nTds = $('td', this);
            //example to show any cell data can be gathered, I used to get my ID from the first coumn in my final code
            //alert( $(nTds[0]).text() );
            var rowId = $(nTds[0]).text();
            // Get All Row Data
            
            $('#myModal' + rowId ).modal('show');
        
            
        }
    } );
	
	$( ":checkbox" ).change(function() {
	    if( $(this).is(':checked') )
	    {
	        Cookies.set( $(this).attr('name'), 1 );
	    }
	    else
	    {
	        Cookies.remove( $(this).attr('name') );

	    }
	} );
	
} );

	</script>
    </head>
    <body>
<?php
// A simple web site in Cloud9 that runs through Apache
// Press the 'Run' button on the top to start the web server,
// then click the URL that is emitted to the Output tab of the console

    $jsonData = file_get_contents( 'data.json');
    $applicants =  json_decode( $jsonData );

//print_r( $applicants );

?>
   
    <div class="container">
        
        <h1 >Dashboard</h1>
       
        <h2 >Applicants</h2>
        <div class="table-responsive">
            <form id="frm-applicant" action="/saveApplicants.php" method="POST">
            <table id="myTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Name</th>
                        <th>Posistion</th>
                        <th>Applied</th>
                        <th>Experience</th>
                        <TH>Favorite</TH>
                    </tr>
                </thead>
                <tbody>
<?php
    foreach( $applicants as $applicant )
    {
        //print_r( $applicant );
        $row = "<TR>";
        $row .= "<TD>" . $applicant->id . "</TD>";
        $row .= "<TD>" . $applicant->name . "</TD>";
        $row .= "<TD>" . $applicant->position . "</TD>";
        $row .= "<TD>" . $applicant->applied . "</TD>";
        $row .= "<TD>" . $applicant->experience . " Year(s)</TD>";
        if( isset( $_COOKIE[$applicant->id . "_fav"] ) )
            $row .= "<TD><input type='checkbox' name='" . $applicant->id . "_fav' checked ></TD>";
        else
            $row .= "<TD><input type='checkbox' name='" . $applicant->id . "_fav' ></TD>";

        $row .= "</TR>";
        echo $row;
    }

?>
                 
                </tbody>
            </table>
            </form>
        </div>
    </div>   
    
<?php foreach( $applicants as $applicant ) : ?>
<!-- Modal -->
<div class="modal fade" id="myModal<?php echo $applicant->id; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo $applicant->name; ?></h4>
      </div>
      <div class="modal-body">
          <p><strong>Position: </strong><?php echo $applicant->position; ?></p>
          <p><strong>Applied: </strong><?php echo $applicant->applied; ?></p>
          <p><strong>Experience: </strong><?php echo $applicant->experience; ?> Year(s)</p>
          <P><strong>Availability: </strong></P>
          <table class="table">
              <thead>
                  <TH>Monday</TH>
                  <TH>Tuesday</TH>
                  <TH>Wednesday</TH>
                  <TH>Thursday</TH>
                  <TH>Friday</TH>
                  <TH>Saturday</TH>
                  <TH>Sunday</TH>
              </thead>
              <tbody>
                  <TD><?php echo ( $applicant->availability->M > 0 ? "<span class='text-success'>Yes</span>" : "<span class='text-danger'>No</span>" ) ?></TD>
                  <TD><?php echo ( $applicant->availability->T > 0 ? "<span class='text-success'>Yes</span>" : "<span class='text-danger'>No</span>" ) ?></TD>
                  <TD><?php echo ( $applicant->availability->W > 0 ? "<span class='text-success'>Yes</span>" : "<span class='text-danger'>No</span>" ) ?></TD>
                  <TD><?php echo ( $applicant->availability->Th > 0 ? "<span class='text-success'>Yes</span>" : "<span class='text-danger'>No</span>" ) ?></TD>
                  <TD><?php echo ( $applicant->availability->F > 0 ? "<span class='text-success'>Yes</span>" : "<span class='text-danger'>No</span>" ) ?></TD>
                  <TD><?php echo ( $applicant->availability->S > 0 ? "<span class='text-success'>Yes</span>" : "<span class='text-danger'>No</span>" ) ?></TD>
                  <TD><?php echo ( $applicant->availability->Su > 0 ? "<span class='text-success'>Yes</span>" : "<span class='text-danger'>No</span>" ) ?></TD>
              </tbody>
          </table>
          <P><strong>Questions: </strong></P>
          <?php foreach( $applicant->questions as $question ) : ?>
          <p><em><?php echo $question->text; ?>: </em><?php echo $question->answer; ?></p>
          <?php endforeach; ?>

          
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
    <?php endforeach; ?>

</body>
</html>