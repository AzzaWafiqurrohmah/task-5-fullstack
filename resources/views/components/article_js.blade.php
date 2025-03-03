@push('script')
    <script>
        document.getElementById('newArticle').addEventListener('click', function() {
            window.location.href = '/articles/create';
        });

        $(document).ready(function (){
            loadData();
        })

        let articles;
        function loadData(){
            $.get('{{route('articles.get')}}', function (data){
                articles = data.articles;
                processData(articles);
            })
        }


        function processData(articles) {
            const articleContainer = document.getElementById('articles');
            let content = '';

            if (articles.length === 0) {
        content = `
            <div class="col-12 text-center">
                <p style="font-size: 18px; color: gray; font-style: italic;">There are no articles available yet</p>
            </div>
        `;
    } else {
        articles.forEach(article => {
            content += `
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="card" style="padding: 0px;">
                        <img src="{{ Storage::url('${article.image}') }}" 
                            alt="Artikel Image" 
                            style="width: 100%; height: 170px; object-fit: cover; border-radius: 8px 8px 0px 0px;">
                        <div class="card-body">
                            <h5 class="card-title">${article.title}</h5>
                            <p class="card-text">${article.content}</p>
                        </div>
                        <div style="text-align: right; margin-bottom: 20px; margin-right: 10px;">
                            <button type="button" class="btn-edit" value="${article.id}"
                                style="border-color: #6a9bfc; background-color: #6a9bfc; color: white; font-size: 14px; border-radius: 5px; padding: 4px 8px; margin-right: 5px;">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <button type="button" class="btn-delete" value="${article.id}"
                                style="border-color: #d32f2f; background-color: #d32f2f; color: white; font-size: 14px; border-radius: 5px; padding: 4px 8px;">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>  
            `;
        });
    }


            articleContainer.innerHTML = content;
        }

        function deleteItem(id) {
            $.ajax({
                url: `/articles/${id}`,
                method: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success(res) {
                    $('#articles').empty();
                    processData(res.data);

                    Swal.fire({
                        icon: 'success',
                        text: res.meta.message,
                        timer: 1500,
                    });
                },
                error(err) {
                    Swal.fire({
                        icon: 'error',
                        text: err.responseJSON,
                        timer: 1500,
                    });
                },
            });
        }

        $('#article').on('click', '#deleteArticle', function(e) {
            Swal.fire({
                icon: 'question',
                text: 'Apakah anda yakin?',
                showCancelButton: true,
                cancelButtonText: 'Batal',
            }).then((res) => {
                if(res.isConfirmed)
                    deleteItem($(this).val());
            });
        });

        $('#article').on('click', '#btn-edit', function(e) {
            window.location.href = "{{ route('articles.edit', 'VALUE') }}".replace('VALUE', $(this).val());
        });

        const editButton = document.getElementById('btn-edit');

        $(document).on('keyup', function (e){
            e.preventDefault();
            let search_string = $('#search').val();
            $.ajax({
                url:"{{route('articles.search')}}",
                method:'GET',
                data:{search_string:search_string},
                success:function (res){
                    let articles = res.articles;
                    console.log(articles);
                    $('#articles').empty();
                    processData(articles);
                }
            })
        })

    </script>
@endpush
