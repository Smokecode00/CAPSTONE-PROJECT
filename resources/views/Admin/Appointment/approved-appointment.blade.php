@extends('adminlte::page')

@section('title', 'Approved Appointment')

@section('css')
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="{{ url('Css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ url('Css/all.min.css') }}">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('Image/logo/mendoza.png') }}">
    {{-- Add here extra stylesheets --}}
    <link rel="stylesheet" href="{{ url('vendor/adminlte/dist/css/custom-admin.css') }}">
    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Custom CSS  -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: "Nunito", sans-serif;
        }
    </style>
    {{-- Notification --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .colored-toast.swal2-icon-success {
            background-color: #a5dc86 !important;
        }

        .colored-toast.swal2-icon-error {
            background-color: #f27474 !important;
        }

        .colored-toast.swal2-icon-warning {
            background-color: #DC3545 !important;
        }

        .colored-toast.swal2-icon-info {
            background-color: #3fc3ee !important;
        }

        .colored-toast.swal2-icon-question {
            background-color: #87adbd !important;
        }

        .colored-toast .swal2-title {
            color: white;
        }

        .colored-toast .swal2-close {
            color: white;
        }

        .colored-toast .swal2-html-container {
            color: white;
        }
    </style>
@stop

@section('content_header')
    <h5 class="fw-bolder" style="color: #343984;"><i class="fa-solid fa-caret-right me-2"></i>Approved Appointment</h5>
    <hr class="mt-0 text-secondary">
    <div class="d-flex justify-content-end">
        @if (session('delete'))
            <script>
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'end',
                    iconColor: 'white',
                    customClass: {
                        popup: 'colored-toast',
                    },
                    showConfirmButton: false,
                    timer: 3000,
                    timerPr0ogressBar: true,
                });
                (async () => {
                    await Toast.fire({
                        icon: 'warning',
                        title: 'Appointment Deleted'
                    })
                })()
            </script>
        @endif
    </div>
@stop

@section('content')
    <div class="row mb-3">
        <div class="col d-flex justify-content-end">
            <form action="{{ route('export.approvedrecord.pdf') }}" method="get">
                @csrf
                <button class="btn btn-danger me-2"><i class="fa-solid fa-file-pdf me-1"></i>Export PDF</button>
            </form>

            <form action="{{ route('export.approved.excel') }}" method="post">
                @csrf
                <button class="btn btn-success">
                    <i class="fa-solid fa-file-arrow-down me-1"></i> Export Excel
                </button>
            </form>
        </div>
    </div>
    <div class="row gy-4 font-web">
        <div class="col bg-primary-subtle p-4 rounded-4" data-aos="fade-up" data-aos-delay="100">
            <table class="table table-striped mb-0 table-bordered">
                <thead class="table-danger">
                    <tr class="text-center">
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Date</th>
                        <th scope="col">Appointment</th>
                        {{-- <th scope="col">Message</th> --}}
                        <th scope="col">Status</th>
                        <th scope="col">Reason</th>
                        <th scope="col">Approval</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $perPage = $approved->perPage();
                        $currentPage = $approved->currentPage();
                        $counter = ($currentPage - 1) * $perPage + 1;
                    @endphp
                    @forelse ($approved->sortByDesc('created_at') as $data)
                        <tr class="text-center">

                            <td class="">{{ $counter++ }}</td>
                            <td class="fw-bold text-start">{{ $data->name }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($data->date)->format('F d, Y') }}</td>
                            <td class="fw-bold">{{ $data->appointment }}</td>
                            {{-- <td>{{ $data->message }}</td> --}}
                            <td class="fw-bold"
                                style="color:
                                @if ($data->status === 'Approved') green
                                @elseif ($data->status === 'Cancelled')
                                    red
                                @else
                                    gray @endif">
                                {{ $data->status }}</td>
                            <td>{{ $data->reason }}</td>
                            {{-- Approval --}}
                            <td class="py-0">
                                <div class="d-flex justify-content-center align-items-center mt-1">
                                    {{-- approved --}}
                                    <form action="/Appointment-List/approvedStatus/{{ $data->id }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary me-2 py-1 my-0">
                                            Approve
                                        </button>
                                    </form>


                                    <a href="#" data-bs-toggle="modal" data-bs-target="#newModal{{ $data->id }}">
                                        <button class="btn btn-danger py-1 my-0">Reject</button>
                                    </a>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center align-items-center mt-0">
                                    {{-- View --}}
                                    <a href="#" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal{{ $data->id }}">
                                        <i class="fas fa-fw fa-magnifying-glass fs-5 me-3 text-success"></i>
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('appointment.delete', ['appointment_id' => $data->id]) }}"
                                        method="post" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn text-danger p-0">
                                            <i class="fas fa-fw fa-trash fs-5"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <div class="row">
                                <div class="col">
                                    <h5>No Approved Appointment</h5>
                                </div>
                            </div>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div>
                {{ $approved->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    {{-- for cancelation --}}



    {{-- Modal --}}
    @foreach ($approved as $data)
        <div class="modal fade" id="exampleModal{{ $data->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-semibold " id="exampleModalLabel">Appointment
                            Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{-- Add your appointment details here --}}
                        <p><b>Name:</b> {{ $data->name }}</p>
                        <p><b>Address:</b> {{ $data->address }}</p>
                        <p><b>Phone:</b> {{ $data->phone }}</p>
                        <p><b>Date:</b>
                            {{ \Carbon\Carbon::parse($data->date)->format('F d, Y') }}</p>
                        <p><b>Appointment:</b> {{ $data->appointment }}</p>
                        <p><b>Message:</b> {{ $data->message }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @foreach ($approved as $data)
        <div class="modal fade" id="newModal{{ $data->id }}" tabindex="-1" aria-labelledby="newModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-semibold" id="newModalLabel">Select Reason for Cancellation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{-- Add your content for the new modal here --}}
                        {{-- Cancelation reason dropdown --}}
                        <form action="/Appointment-List/canceledStatus/{{ $data->id }}" method="POST">
                            @csrf
                            <label for="reason">Select Reason for Cancellation:</label><br>
                            <select id="reason" name="reason" class="form-control">
                                <option class="text-muted">Select Reason</option>
                                <option value="The type of service requested is not available">The type of service
                                    requested is not available</option>
                                <option value="Prioritization of emergencies">Prioritization of emergencies</option>
                                <option value="Incorrect client info">Incorrect client info</option>
                                <option value="Schedule conflict">Schedule conflict</option>
                                <option value="Equipment failure">Equipment failure</option>
                                <option value="Holiday closure">Holiday closure</option>

                            </select><br><br>
                            <button type="submit" class="btn btn-danger">Submit</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        </div>
    @endforeach
@stop


@section('js')

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    {{-- Font Awesome --}}
    <script src="https://kit.fontawesome.com/5c14b0052b.js" crossorigin="anonymous"></script>
    <script>
        console.log("Hi, Welcome to E.A MENDOZA APPOINTMENT SYSTEM!");
    </script>
@stop
