<?php

	namespace SOS
	{
		class ArticlesController
		{
			const MAX_CONTENT_SIZE = 500;
			const SPLIT_PER_PAGE = 4;
			private $view;
			private $db;
			private $actualPage;

			public function __construct(ArticleView $articleView)
			{
				$this->view = $articleView;
				$this->db = new Articles(self::SPLIT_PER_PAGE);
				$this->actualPage = (empty($_GET['nxt']) || !is_numeric($_GET['nxt'])) ? 0 : (int)$_GET['nxt'];
				if ($this->actualPage >= $this->db->getAmountOfPages())
					$this->actualPage = 0;
			}

			public function makeOne(int $id): string
			{
				$article = $this->db->selectById($id);
				if (empty($article)) return '';
				$this->prepareData($article);
				return $this->view->showArticle($article);
			}

			public function makeAllPerPage(): string
			{
				$output = '';
				$articles = $this->db->getArticlesForPage($this->actualPage);
				foreach ($articles as $article)
				{
					$this->prepareData($article);
					if (strlen($article['content']) > self::MAX_CONTENT_SIZE)
					{
						$article['content'] = preg_replace('/\s+?(\S+)?$/', '', substr($article['content'], 0, self::MAX_CONTENT_SIZE));
						$article['content'] .= '...<p class="readmore"><a href="article/'.$article['id'].'">Czytaj dalej</a></p>';
					}
					$output .= $this->view->showArticle($article);
				}
				return $output;
			}

			public function makeNavBar(): string
			{
				$data = ['prev-button' => '', 'prev-1' => '', 'prev-2' => '', 'next-button' => '', 'next-2' => '', 'next-1' => '', 'current' => ''];

				$data['current'] = $this->view->showLinkCurrent($this->actualPage, $this->actualPage+1);

				if ($this->actualPage != 0)
				{
					$data['prev-button'] = $this->view->showLinkPrev($this->actualPage-1);
					$data['prev-1'] = $this->view->showLink($this->actualPage-1, $this->actualPage);
				}

				if ($this->actualPage >= 2)
					$data['prev-2'] = $this->view->showLink($this->actualPage-2, $this->actualPage-1);

				if ($this->actualPage+1 < $this->db->getAmountOfPages())
				{
					$data['next-button'] = $this->view->showLinkNext($this->actualPage+1);
					$data['next-1'] = $this->view->showLink($this->actualPage+1, $this->actualPage+2);
				}

				if ($this->actualPage+2 < $this->db->getAmountOfPages())
					$data['next-2'] = $this->view->showLink($this->actualPage+2, $this->actualPage+3);

				return $this->view->showNavBar($data);
			}

			private function prepareData(array &$article): void
			{
				$user = new User;
				$user->setId($article['id_user']);
				$article['author'] = $user->selectThis(['login']);
				$article['date'] = (new \DateTime($article['date']))->format('d.m.Y');
			}
		}
	}

?>
