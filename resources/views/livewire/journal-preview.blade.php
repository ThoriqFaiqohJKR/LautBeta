<div class="">
    @if($pdf_url)
    <div class="_df_book" id="flipbook_journal" source="{{ $pdf_url }}"></div>
    @else
    <p class="text-red-600">File tidak ditemukan.</p>
    @endif
</div>
 