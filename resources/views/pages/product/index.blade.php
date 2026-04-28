@extends('layouts.app')

@section('title', 'Produk')

@section('content')

    <x-navbar />

    @livewire('product-index')

    <x-footer />

@endsection

