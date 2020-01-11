<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</head>
<body>
    <br><br>
    <div class="container">
        <h2 align="center">PHP Filesystem with Ajax and JQuery</h2>
        <br>
        <div align="right">
            <button type="button" name="create_folder" id="create_folder" class="btn btn-success">Create</button>
        </div>
        <br>
        <div id="folder_table" class="table-responsive">

        </div>

    </div>

    
</body>
</html>

<div id="folderModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span id="change_title">Create Folder</span></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Enter Folder Name <input type="text" name="folder_name" id="folder_name" class="form-control"></p>
                <br>
                <input type="hidden" name="action" id="action">
                <input type="hidden" name="old_name" id="old_name">
                <input type="button" name="folder_button" id="folder_button" class="btn btn-info" value="Create">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="uploadModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span id="change_title">Upload File </span></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form method="post" id="upload_form" enctype="multipart form/data">
                    <p>Select Image <input type="file" name="upload_file"></p>
                    <br>
                    <input type="hidden" name="hidden_folder_name" id="hidden_folder_name">
                    <input type="submit" name="upload_button" class="btn btn-info" value="Upload">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="filelistModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span id="change_title">File List</span></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="file_list">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){

        load_folder_list();
        function load_folder_list() {
            var action = "fetch";
            $.ajax({
                url : "action.php",
                method: "POST",
                data: {action:action},
                success: function(data){
                    $('#folder_table').html(data);
                }
            })
        }

        $(document).on('click', '#create_folder', function(){
            $('#action').val('create');
            $('#folder_name').val('');
            $('#folder_button').val('Create');
            $('#old_name').val('');
            $('#change_title').text('Create Folder');
            $('#folderModal').modal('show');
        })

        $(document).on('click', '#folder_button', function(){
            var folder_name = $('#folder_name').val();
            var action = $('#action').val();
            var old_name = $('#old_name').val();
            if(folder_name != ''){
                $.ajax({
                    url:'action.php',
                    method:'POST',
                    data:{folder_name:folder_name, old_name:old_name, action:action},
                    success:function(data){
                        $('#folderModal').modal('hide');
                        load_folder_list();
                        alert(data);
                    }
                })
            }else{
                alert("Enter Folder Name");
            }
        });
        $(document).on('click', '.update', function(){
            var folder_name = $(this).data("name");
            $('#old_name').val(folder_name);
            $('#folder_name').val(folder_name);
            $('#action').val('change');
            $('#folder_button').val('Update');
            $('#change_title').text('Change Folder Name');
            $('#folderModal').modal('show');

        });

        $(document).on('click', '.upload', function(){
            var folder_name = $(this).data("name");
            $("#hidden_folder_name").val(folder_name);
            $("#uploadModal").modal('show');
        });

        $('#upload_form').on('submit', function(){
            $.ajax({
                url: "upload.php",
                method: "POST",
                data:new FormData(this),
                contentType:false,
                cache:false,
                processData:false,
                success:function(data){
                    load_folder_list();
                    alert(data);
                }
            })
        });

        $(document).on('click', '.view_files', function(){
            var folder_name = $(this).data("name");
            var action = "fetch_files";
            $.ajax({
                url: "action.php",
                method: "POST",
                data: {action:action, folder_name:folder_name},
                success:function(data){
                    $('#file_list').html(data);
                    $('#filelistModal').modal('show');
                }
            })
        });

        $(document).on('click', '.remove_file', function(){
            var path = $(this).attr("id");
            var action = "remove_file";
            if(confirm("Are you sure you want to remove this file?")){
                $.ajax({
                    url: "action.php",
                    method:"POST",
                    data:{path:path, action:action},
                    success:function(data){
                        alert(data);
                        $('#filelistModal').modal('hide');
                        load_folder_list();
                    }
                });
            }else {
                return false;
            }
        });

        $(document).on('click', '.delete', function(){
            var folder_name = $(this).data("name");
            var action = 'delete';
            if (confirm("Are you sure you want to delete this folder?")){
                $.ajax({
                    url: "action.php",
                    method: "POST",
                    data:{folder_name:folder_name, action:action},
                    success:function(data){
                        load_folder_list();
                        alert(data);
                    }
                })
            }
        })
    });
</script>