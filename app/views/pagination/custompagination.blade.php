<b>Total {{$paginator->getTotal()}}.</b>
Showing {{$paginator->getFrom()}}
To {{$paginator->getTo()}}.

@if ($paginator->getLastPage() > 1)
<?php 
  $currentPage = $paginator->getCurrentPage();
  $lastPage = $paginator->getLastPage();
  $previousPage = ($currentPage > 1) ? $currentPage - 1 : 1;
?>  
<!-- ul class="ui pagination menu" -->  
  <br/>Pages: 
  @if ($currentPage != 1)
    <a href="{{ $paginator->getUrl($previousPage) }}"
      class="item{{ ($paginator->getCurrentPage() == 1) ? ' disabled' : '' }}">
      <i class="icon left arrow"></i>< Previous
    </a>
  @endif
  @for ($i = 1; $i <= $paginator->getLastPage(); $i++)
  @if ($currentPage == $i)
    {{ $i }}
  @else
    <a href="{{ $paginator->getUrl($i) }}"
      class="item{{ ($paginator->getCurrentPage() == $i) ? ' active' : '' }}">
        {{ $i }}
    </a>
  @endif
  @endfor
  @if ($currentPage != $lastPage)
    <a href="{{ $paginator->getUrl($paginator->getCurrentPage()+1) }}"
      class="item{{ ($paginator->getCurrentPage() == $paginator->getLastPage()) ? ' disabled' : '' }}">
      Next ><i class="icon right arrow"></i>
    </a>
  @endif
<!-- /ul --> 
@else
<br/> 
@endif