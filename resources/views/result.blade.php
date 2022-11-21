@extends('main')

@section('content')

    <div class="relative flex items-top justify-center min-h-screen backGround sm:items-center py-4 sm:pt-0">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            <div class="mt-8 bg-white backGround overflow-hidden shadow sm:rounded-lg">
                <div class="text-center"><h3>Crawled Pages:</h3>
                    <div>
                        <table class="table backGround border-t">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Page URL</th>
                                <th scope="col">Response Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($webPages as $id => $webPage)
                                <tr>
                                    <th scope="row">{{ $id + 1 }}</th>
                                    <td>{{ $webPage->getUrl() }}</td>
                                    <td>{{ $webPage->getHttpResponse() }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <br class="text-center">
                        <h3>Web Page Analytics:</h3>
                        <div>
                            <table class="table table-dark bg-gray-100 border-t">
                                <thead>
                                <tr>
                                    <th scope="col">Pages Scanned</th>
                                    <th scope="col">Unique Images</th>
                                    <th scope="col">Unique Internal Links</th>
                                    <th scope="col">Unique External Links</th>
                                    <th scope="col">Average Page Load</th>
                                    <th scope="col">Average Word Count</th>
                                    <th scope="col">Average Title Length</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>{{ $analytics->getNumberOfCrawledPages() }}</td>
                                    <td>{{ $analytics->getNumberOfUniqueImages() }}</td>
                                    <td>{{ $analytics->getNumberOfUniqueInternalLinks() }}</td>
                                    <td>{{ $analytics->getNumberOfUniqueExternalLinks() }}</td>
                                    <td>{{ $analytics->getAveragePageLoad() }}</td>
                                    <td>{{ $analytics->getAverageWordCount() }}</td>
                                    <td>{{ $analytics->getAverageTitleLength() }}</td>
                                </tr>
                                </tbody>
                            </table>

                        </div>
                        {{ Form::open(array('route' => 'index', 'method' => 'get')) }}

                        {{ Form::submit('Reset', ['class'=> 'button buttonReset']) }}

                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
