<header class="inset-x-0 top-0 z-50 fixed">
    <x-true-nav-bar/>
</header>
@if(!request()->is("/"))
    <div class="inset-x-0 invisible">
        <x-true-nav-bar :disabled="true"/>
    </div>
@endif
