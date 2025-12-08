<x-admin-app-layout :title="'Website Setting'">
    <div class="row g-2" id="columns-container">
        <div class="row py-10 pt-0">
            <div class="col-lg-2">
                <div class="custom-fixed-top">
                    <div class="d-flex flex-column flex-md-row rounded border bg-white">
                        @include('admin.pages.setting.partials.tab_trigger')
                    </div>
                </div>
            </div>

            <div class="card col-lg-10">
                <form class="form" action="{{ route('admin.settings.updateOrCreate') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="card-body">

                        <div class="tab-content bg-white p-5" id="myTabContent">
                            <div class="tab-pane fade active show" id="generalInfo" role="tabpanel">
                                <div class="row">
                                    <div class="col-lg-12 general_info_container">
                                        @include('admin.pages.setting.partials.general_info')
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="footer" role="tabpanel">
                                <div class="row">
                                    <div class="col-lg-12 footer_container">
                                        @include('admin.pages.setting.partials.footer')
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="businessHours" role="tabpanel">
                                <div class="row">
                                    <div class="col-lg-12 business_hours_container">
                                        @include('admin.pages.setting.partials.business_hours')
                                    </div>
                                </div>
                            </div>


                            <div class="tab-pane fade" id="seo" role="tabpanel">
                                <div class="row">
                                    <div class="col-lg-12 seo_container">
                                        @include('admin.pages.setting.partials.seo')
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="socialLinks" role="tabpanel">
                                <div class="row">
                                    <div class="col-lg-12 social_links_container">
                                        @include('admin.pages.setting.partials.social_links')
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="advance" role="tabpanel">
                                <div class="row">
                                    <div class="col-lg-12 advance_container">
                                        @include('admin.pages.setting.partials.advance')
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="setting" role="tabpanel">
                                <div class="row">
                                    <div class="col-lg-12 setting_container">
                                        @include('admin.pages.setting.partials.setting')
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer py-3">
                        <div class="text-end">
                            <button type="submit" class="btn btn-outline btn-outline-info btn-active-info rounded-1"
                                id="saveSettingsBtn">
                                {{ __('Submit') }}
                            </button>
                            {{-- <x-metronic.button type="submit" class="primary">
                            </x-metronic.button> --}}
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
        @include('admin.pages.setting.partials.setting_js')
    @endpush
</x-admin-app-layout>
