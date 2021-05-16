@extends('layout.main')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Boards</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Boards</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Boards list</h3>
            </div>

            @if (session('success'))
                <div class="alert alert-success" role="alert">{{session('success')}}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger" role="alert">{{session('error')}}</div>
            @endif

            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Name</th>
                            <th>User</th>
                            <th>Members</th>
                            <th style="width: 40px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($boards as $board)
                            <tr>
                                <td>{{$board->id}}</td>
                                <td>
                                    <a href="{{route('board.view', ['id' => $board->id])}}" class="link">{{$board->name}}</a>
                                </td>
                                <td>{{$board->user->name}}</td>
                                <td>
                                    {{count($board->boardUsers)}}
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-xs btn-primary"
                                                type="button"
                                                data-board="{{json_encode($board)}}"
                                                data-toggle="modal"
                                                data-target="#boardEditModal">
                                            <i class="fas fa-edit"></i></button>
                                        <button class="btn btn-xs btn-default"
                                                type="button"
                                                data-board="{{json_encode($board)}}"
                                                data-toggle="modal"
                                                data-target="#boardEditModalAjax">
                                            <i class="fas fa-edit"></i></button>
                                        <button class="btn btn-xs btn-danger"
                                                type="button"
                                                data-board="{{json_encode($board)}}"
                                                data-toggle="modal"
                                                data-target="#boardDeleteModal">
                                            <i class="fas fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- /.card-body -->
            <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                    @if ($boards->currentPage() > 1)
                        <li class="page-item"><a class="page-link" href="{{$boards->previousPageUrl()}}">&laquo;</a></li>
                        <li class="page-item"><a class="page-link" href="{{$boards->url(1)}}">1</a></li>
                    @endif

                    @if ($boards->currentPage() > 3)
                        <li class="page-item"><span class="page-link page-active">...</span></li>
                    @endif
                    @if ($boards->currentPage() >= 3)
                        <li class="page-item"><a class="page-link" href="{{$boards->url($boards->currentPage() - 1)}}">{{$boards->currentPage() - 1}}</a></li>
                    @endif

                    <li class="page-item"><span class="page-link page-active">{{$boards->currentPage()}}</span></li>

                    @if ($boards->currentPage() <= $boards->lastPage() - 2)
                        <li class="page-item"><a class="page-link" href="{{$boards->url($boards->currentPage() + 1)}}">{{$boards->currentPage() + 1}}</a></li>
                    @endif

                    @if ($boards->currentPage() < $boards->lastPage() - 2)
                        <li class="page-item"><span class="page-link page-active">...</span></li>
                    @endif

                    @if ($boards->currentPage() < $boards->lastPage() )
                        <li class="page-item"><a class="page-link" href="{{$boards->url($boards->lastPage())}}">{{$boards->lastPage()}}</a></li>
                        <li class="page-item"><a class="page-link" href="{{$boards->nextPageUrl()}}">&raquo;</a></li>
                    @endif
                </ul>
            </div>
        </div>
        <!-- /.card -->

        <div class="modal fade" id="boardEditModal">
            <div class="modal-dialog">
                <form action="{{route('boards.update')}}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Edit board</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input id="boardEditName"/>
                            <input type="hidden" name="id" id="boardEditId" value="" />
                            <div class="form-group">
                                <b>Members select: to be implemented...</b>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="boardEditButton">Save changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="boardEditModalAjax">
            <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Edit Board</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-danger" id="boardEditAlert"></div>
                            <input id="boardEditNameAjax">
                            <input type="hidden" id="boardEditIdAjax" />
                            <div class="form-group">
                                <b>Members select: to be implemented...</b>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="boardEditButtonAjax">Save changes</button>
                        </div>
                    </div>
            </div>
        </div>

        <div class="modal fade" id="boardDeleteModal">
            <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <form action="{{ route('boards.delete', $board->id) }}" method="post">
                        @csrf
                        <div class="modal-body text-center">
                            <div id="boardDeleteAlert"></div>
                            <input type="hidden" id="boardDeleteId" value="" />
                            <p>Are you sure you want to delete: <span id="boardDeleteName"></span>?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger" id="boardDeleteButton">Yes, Delete</button>
                        </div>
                </form>
            </div>
            </div>
        </div>

    </section>
    <!-- /.content -->
@endsection