@extends('layouts.custom')

@section('content')
    <!-- DataTales Example -->
    <div class="card">
        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between"> 
                <h6 class="m-0 font-weight-bold text-danger">Parcel collections</h6>

                <div class="d-flex align-items-center"> 
                    <!-- Counter positioned just before the search input -->
                    <span class="text-primary counter mr-2"></span>

                    <div class="input-group input-group-sm mr-2" style="width: 250px;">
                        <input type="text" id="tableSearch" class="form-control" placeholder="Search client requests...">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                        </div>
                    </div>

                    <button type="button" class="btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#createClientRequest">
                        <i class="fas fa-plus fa-sm text-white"></i> Create Client Request
                    </button>
                </div>
            </div>

            <!-- Modal -->
            <form action="{{ route('clientRequests.store') }}" method="POST">
                @csrf
                <div class="modal fade" id="createClientRequest" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document"> <!-- Added modal-lg for wider layout -->
                        <div class="modal-content">
                            <div class="modal-header bg-gradient-primary">
                                <h5 class="modal-title text-white" id="exampleModalLabel">Create Client Request</h5>
                                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered results" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Request ID</th>
                            <th>Client</th>
                            <th>Pick-up Location</th>
                            <th>Date Requested</th>
                            <th>Rider</th>
                            <th>Vehicle</th>
                            <th>Description of goods</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        <tr class="table-warning no-result text-center" style="display: none;">
                            <td colspan="10">
                                <i class="fa fa-warning text-danger"></i> No result found.
                            </td>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Request ID</th>
                            <th>Client</th>
                            <th>Pick-up Location</th>
                            <th>Date Requested</th>
                            <th>Rider</th>
                            <th>Vehicle</th>
                            <th>Description of goods</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
                <script>
                document.addEventListener("DOMContentLoaded", function () {
                    const searchInput = document.getElementById("tableSearch");
                    const tableRows = document.querySelectorAll(".results tbody tr");
                    const counter = document.querySelector(".counter"); // optional
                    const noResult = document.querySelector(".no-result"); // optional

                    searchInput.addEventListener("input", function () {
                        const searchTerm = searchInput.value.toLowerCase().trim();
                        let matchCount = 0;

                        tableRows.forEach(row => {
                            const rowText = row.textContent.toLowerCase().replace(/\s+/g, " ");
                            const terms = searchTerm.split(" ");
                            const matched = terms.every(term => rowText.includes(term));

                            if (matched) {
                                row.style.display = "";
                                row.setAttribute("visible", "true");
                                matchCount++;
                            } else {
                                row.style.display = "none";
                                row.setAttribute("visible", "false");
                            }
                        });

                        if (counter) {
                            counter.textContent = matchCount + " item" + (matchCount !== 1 ? "s" : "");
                        }

                        if (noResult) {
                            noResult.style.display = matchCount === 0 ? "block" : "none";
                        }
                    });
                });
                </script>
            </div>
        </div>
    </div>
@endsection
