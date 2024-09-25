<ul class="list-unstyled" style="margin-left: 20px;">
    @foreach($subcategories as $subcategory)
        <li>
            <h5>
                <a class="custom-link" href="{{ route('curricula.by_category', $subcategory->id) }}">{{ $subcategory->name }}</a>
            </h5>
            @if($subcategory->subcategories)
                @include('layouts.partials.subcategories', ['subcategories' => $subcategory->subcategories])
            @endif
        </li>
    @endforeach
</ul>
