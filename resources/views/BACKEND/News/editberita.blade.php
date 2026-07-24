@extends('backend.layout.admin')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          </div>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
        <style>
    .custom-file-upload:hover {
        border-color: #007bff;
        background-color: #eaf2ff;
    }

    .custom-file-upload input[type="file"] {
        display: none;
    }

    .custom-file-upload .icon {
        font-size: 2.5rem;
        color: #007bff;
        margin-bottom: 10px;
    }

    .custom-file-upload .text {
        font-size: 1rem;
        color: #666;
    }

    .image-preview {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 300px;
        border: 2px dashed #ddd;
        border-radius: 8px;
        position: relative;
        overflow: hidden;
        background-color: #f8f8f8;
        padding: 10px;
        transition: border-color 0.3s ease;
    }

    .image-preview:hover {
        border-color: #4B49AC;
    }

    .image-preview img {
        max-height: 100%;
        max-width: 100%;
        object-fit: cover;
        display: none;
    }

    .preview-text {
        font-size: 16px;
        color: #aaa;
    }
</style>
  <body>
    <h1 class="text-center mb-4">Edit Berita</h1>
    <div class="container">
        <div class="row justify-content-center">
          <div class="col-8">
            <div class="card">
              <div class="card-body">
                <form action="/updatedata/{{ $data->id }}" method="post" enctype="multipart/form-data">
                  @csrf
                  <div class="mb-3">
                    <label for="image" class="form-label">Gambar</label>
                    <input type="file" id="image" name="foto" class="form-control">
                    <div class="image-preview" id="thumbnailInput">
                        <img src="{{ asset('fotoberita/' . $data->foto) }}" alt="Image Preview" id="previewImage" style="display: block;">
                    </div>
                </div>
  
                <div class="mb-3">
                    <label for="judulnews" class="form-label">Judul</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $data->title) }}">
                </div>

                <div class="mb-3">
                    <label for="kontributornews" class="form-label">Kontributor</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $data->name) }}">
                </div>

                <div class="mb-3">
                    <label for="category_id" class="form-label">Kategori</label>
                    <select name="category_id" class="form-control" id="category_id">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $data->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="excerpt" class="form-label">Short Content Berita</label>
                    <textarea class="form-control" name="excerpt" id="excerpt">{{ old('excerpt',$data->excerpt) }}</textarea>
                </div>
  
                <div class="mb-3">
                    <label for="content" class="form-label">Content Berita</label>
                    <textarea class="form-control" name="content" id="summernote">{{ old('content', $data->content) }}</textarea>
                </div>
  
                <div class="mb-3">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" class="form-control @error('date')is-invalid @enderror" value="{{ old('date', $data->date) }}" name="date" id="date">
                </div>
  
                <div class="mb-3">
                    <label for="hit" class="form-label">Hit</label>
                    <input type="text" name="hit" class="form-control" value="{{ old('hit', $data->hit) }}" id="hit">
                </div>
                
                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary">Save Change</button>
                </div>
                </form>
              </div>
             </div>
          </div>
        </div>
    </div>

    <!-- jQuery (required for Summernote) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Summernote JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

    <script>
      // Initialize Summernote
      $(document).ready(function() {
          $('#summernote').summernote({
              height: 300,
              toolbar: [
                  ['style', ['style']],
                  ['font', ['bold', 'underline', 'clear']],
                  ['fontname', ['fontname']],
                  ['color', ['color']],
                  ['para', ['ul', 'ol', 'paragraph']],
                  ['table', ['table']],
                  ['insert', ['link', 'picture', 'video']],
                  ['view', ['fullscreen', 'codeview', 'help']]
              ]
          });
          
          // Image preview handling
          document.getElementById("image").addEventListener("change", function () {
              const file = this.files[0];
              const previewImage = document.getElementById("previewImage");
              const previewText = document.getElementById("previewText");
      
              if (file) {
                  const reader = new FileReader();
                  reader.onload = function (e) {
                      previewImage.src = e.target.result;
                      previewImage.style.display = "block";
                      previewText.style.display = "none";
                  };
                  reader.readAsDataURL(file);
              } else {
                  previewImage.style.display = "none";
                  previewText.style.display = "block";
              }
          });
      });
  </script>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  </body>
</div>
@endsection