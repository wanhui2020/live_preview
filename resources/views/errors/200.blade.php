@extends('errors::minimal')

@section('title', '系统提示')
@section('code', '200')
@section('message', __($exception->getMessage() ?: 'Service Unavailable'))

