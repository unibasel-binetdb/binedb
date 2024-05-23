<x-backpack::menu-item title="Bibliotheken" icon="la la-book" :link="backpack_url('library')" />
<x-backpack::menu-dropdown title="Mitarbeitende" icon="la la-user-friends">
    <x-backpack::menu-dropdown-item title="Personen" icon="la la-user-circle" :link="backpack_url('person')" />
    <x-backpack::menu-dropdown-item title="Funktionen" icon="la la-briefcase" :link="backpack_url('person-function')" />
</x-backpack::menu-dropdown>

<x-backpack::menu-item title="Exporte" icon="la la-file-export" :link="backpack_url('export')" />

<x-backpack::menu-dropdown title="Einstellungen" icon="la la-cogs">
    <x-backpack::menu-dropdown-item title="Benutzer" icon="la la-user" :link="backpack_url('user')" />
</x-backpack::menu-dropdown>