@extends('front.layouts.app')

@section('main')

<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Job Applications</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                @include('admin.sidebar')
            </div>
            <div class="col-lg-9">
                @include('front.layouts.message')
                <div class="card border-0 shadow mb-4">
                    <div class="card-body card-form">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="fs-4 mb-1">Applications</h3>
                            </div>
                            
                        </div>
                        <div class="table-responsive">
                            <table class="table ">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col">Job Title</th>
                                        <th scope="col">Applied By</th>
                                        <th scope="col">Created By</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="border-0">
                                    @if ($applications->isNotEmpty())
                                        @foreach ($applications as $application)
                                        <tr class="active">
                                            <td>
                                                {{ $application->addJob->title }}
                                            </td>
                                            <td>
                                                {{ $application->user->name }}
                                            </td>
                                            <td>
                                                {{ $application->employer->name }}
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($application->created_at)->format('d M, Y') }}</td>
                                            @if($application->addJob-> status == 1)
                                            <td>
                                                Active
                                            </td>
                                            @else
                                            <td>
                                                Block
                                            </td>
                                            @endif
                                            <td>                                   <button type="submit" class="bg bg-danger" onclick="deleteJob({{ $application->id }})"><i class="fa fa-trash" aria-hidden="true"></i> Delete
                                            </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td colspan="3" class="text-center">No data available</td>

                                        </tr>
                                    @endif
                                </tbody>
                                
                            </table>
                        </div>
                        <div>
                            {{ $applications->links() }}
                        </div>
                    </div>
                </div>
                
              
            </div>
        </div>
    </div>
</section>

@endsection

@section('customJs')
    <script type="text/javascript">
    function deleteJob(id){
    if(confirm("Are you sure you want to delete?")){
        $.ajax({
            url : '{{ route("admin.applications.destroy") }}',
            type: 'delete',
            data : {id: id},
            dataType : 'json',
            success : function(response){
                window.location.href='{{ route("admin.applications") }}';
            }
        });
    }
    }
    </script>
@endsection
