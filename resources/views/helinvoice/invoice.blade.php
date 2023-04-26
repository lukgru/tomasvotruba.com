@php
    /** @var \App\ValueObject\FuelInvoice|null $fuel_invoice */
@endphp

@extends('layout/layout_base')

@section('wide_content')
    <div class="container" style="max-width: 80rem">
        <h1>Convert PDF Invoice to Clean Table 🧼️️</h1>

        <div class="row">
            <div class="col-12 col-md-6 d-block">
                <div class="card">
                    <div class="card-body">
                        <p class="mb-2">1. Pick PDF Invoice from your computer ↓</p>

                        <form
                            method="POST"
                            enctype="multipart/form-data"
                        >
                            <div class="form-group">
                                <input
                                    type="file"
                                    class="form-control me-3"
                                    name="{{ \App\Enum\InputName::INVOICE_PDF }}"
                                    accept="application/pdf"
                                    required
                                >
                            </div>

                            <div class="mt-4">
                                <p class="mb-2">2. Upload it ↓</p>
                                <button type="submit" class="btn btn-success">Submit PDF</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <br>
        <br>
        <br>

        @if ($fuel_invoice instanceof \App\ValueObject\FuelInvoice)
            <p>
                Ordine: 4222131633
            </p>

            @todo add invoice number PJxxx
            @todo invoice date n PJ.... del 31/03/2022

            @importo - total price with tax

            <table class="table table-bordered table-responsive table-striped">
                <thead class="table-dark">
                    <tr class="text-center align-middle">
                        <th>#</th>
                        <th>Car Plate</th>
                        <th>Driver</th>
                        <th>Date</th>
                        <th>Base Price</th>
                        <th>Tax<br>(22 %)</th>
                        <th>Price with Tax</th>
                        <th>FB<br>(40 %)</th>
                        <th>FD<br>(60 %)</th>
                    </tr>
                </thead>

                @foreach ($fuel_invoice->getCarReports() as $car_report)
                    <tr>
                        <td class="text-end">
                            {{ $loop->index + 1 }}
                        </td>
                        <td style="white-space: nowrap">
                            {{ $car_report->getReadablePlateId() }}
                            <br>
                            <span class="text-secondary">
                                Telepass
                            </span>
                        </td>
                        <td style="font-size: .8rem">
                            {{ $car_report->getDriverName() }}

                            <br>

                            <span class="text-secondary">
                                {{ $car_report->getCarName() }}
                            </span>
                        </td>

                        <td style="font-size: .8rem">
                            {{ $car_report->getDateRange() }}
                        </td>

                        <td class="text-end">
                            {{ nice_number($car_report->getBasePrice()) }}&nbsp;€
                        </td>

                        <td class="text-end">
                            {{ nice_number($car_report->getTax()) }} €
                        </td>

                        <td class="text-end">
                            <strong>
                                {{ nice_number($car_report->getTotalPrice()) }}&nbsp;€
                            </strong>
                        </td>

                        <td class="text-end">
                            {{ nice_number($car_report->getFB()) }}&nbsp;€
                        </td>

                        <td class="text-end">
                            {{ nice_number($car_report->getFD()) }}&nbsp;€
                        </td>
                    </tr>
                @endforeach

                <tr
                    @class([
                        'bg-success-subtle' => $fuel_invoice->areTotalPricesMatching(),
                        'bg-error-subtle' => ! $fuel_invoice->areTotalPricesMatching(),
                        'text-black-50',
                    ])
                >
                    <th colspan="4">Summary Check</th>

                    <td class="text-end">
                        {{ nice_number($fuel_invoice->getCarReportsBasePriceTotal()) }}&nbsp;€
                    </td>

                    <td class="text-end">
                        {{ nice_number($fuel_invoice->getCarReportsTaxTotal()) }}&nbsp;€
                    </td>

                    <td class="text-end">
                        <strong>
                            {{ nice_number($fuel_invoice->getCarReportsTotalPrice()) }}&nbsp;€
                        </strong>
                    </td>

                    <td colspan="2"></td>
                </tr>
            </table>

            <br>
            <br>

            @if ($fuel_invoice->areTotalPricesMatching())
                <div class="card bg-success text-white border-5">
                    <div class="card-body">
                        <div style="font-size: 3rem" class="float-end mt-3 me-2">🥳️</div>

                        <p>
                            The table records <strong>total price MATCHES</strong> the invoice total:
                            <strong>{{ nice_number($fuel_invoice->getTotalPriceAfterDiscount()) }}&nbsp;€</strong>
                        </p>

                        <p>
                            Good job!
                        </p>

                    </div>
                </div>
            @else
                <div class="card bg-danger text-white border-5">
                    <div class="card-body">
                        <div style="font-size: 3rem" class="float-end mt-3 me-2">😿️</div>

                        <p>
                            The table records <strong>total price DOES NOT match</strong> the invoice total:
                            <strong>{{ nice_number($fuel_invoice->getTotalPriceAfterDiscount()) }} €</strong>
                        </p>

                        <p>
                            Nooooooo!
                        </p>

                    </div>
                </div>
            @endif

            <br>
            <br>

            <p class="text-secondary">❤ Made for my Love️️, so she has more time for what matters the most to her...
                (taking care
                of me 😸)</p>
        @else
            <p>
                Your helpful table will ge generated here.
            </p>
        @endif
    </div>
@endsection
