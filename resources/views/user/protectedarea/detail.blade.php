@extends('layouts.form')

@section('content')
<detail-protectedarea-component
        :value="{{$model}}"
        :year="{{$datasetWithYear}}"
        :dataset="{{$occurrencesDataset}}"
        :province="{{$occurrencesProvince}}"
        :region="{{$occurrencesRegion}}"
        :orga = "{{$datasetOrga}}"
        :listSpecies = "{{ json_encode($listSpecies)}}"
        :tabdefault = "{{$tab}}"
        >
</detail-protectedarea-component>
@endsection
