import Welcome from "@/Components/Welcome.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import JetApplicationMark from "@/Components/ApplicationMark.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import JetDropdown from "@/Components/Dropdown.vue";
import JetDropdownLink from "@/Components/DropdownLink.vue";
import IndexView from "@/Views/IndexView.vue";
import Datatable from "@/Components/Datatable.vue";


export default function (app) {
    app.component('welcome', Welcome);
    app.component('head', Head);
    app.component('link', Link);
    app.component('applicationMark', JetApplicationMark);
    app.component('ApplicationLogo', ApplicationLogo);
    app.component('dropdown', JetDropdown);
    app.component('dropdownLink', JetDropdownLink);
    app.component('indexView', IndexView);
    app.component('DataTable', Datatable);

}
