<?php

	namespace SOS
	{
		class ArticleView
		{
			public function showArticle(array $data): string
			{
				return <<<EOF
				<div class="row">
					<section class="col">
						<article>
							<section>
								<h3>{$data['header']}</h3>
								<p class="date">Opublikowano: {$data['date']}</p>
							</section>

							<p class="content">{$data['content']}</p>
							<p class="author">Autor: <a href="profile/{$data['id_user']}">{$data['author']}</a></p>
						</article>
					</section>
				</div>
EOF;
			}

			public function showNavBar(array $data): string
			{
				return <<<EOF
				<nav class="articleNavBar" aria-label="Nawigacja artykułów">
				  <ul class="pagination justify-content-center">
						{$data['prev-button']}
						{$data['prev-2']}
						{$data['prev-1']}
						{$data['current']}
						{$data['next-1']}
						{$data['next-2']}
						{$data['next-button']}
				  </ul>
				</nav>
EOF;
			}

			public function showLinkPrev(int $n): string
			{
				return $this->showLink($n, '<span aria-hidden="true" id="navBarPrev">&laquo;</span>');
			}

			public function showLinkNext(int $n): string
			{
				return $this->showLink($n, '<span aria-hidden="true" id="navBarNext">&raquo;</span>');
			}

			public function showLink(int $n, $value): string
			{
				return '<li class="page-item"><a class="page-link" href="'.$this->getHref($n).'">'.$value.'</a>';
			}

			public function showLinkCurrent(int $n, $value): string
			{
				return '<li class="page-item active" aria-current="page"><a class="page-link" href="'.$this->getHref($n).'">'.$value.'<span class="sr-only">Obecnie</span></a>';
			}

			private function getHref(int $n): string
			{
				if ($n == 0) return Config::get()->path('main');
				return 'news/'.$n;
			}
		}
	}

?>
