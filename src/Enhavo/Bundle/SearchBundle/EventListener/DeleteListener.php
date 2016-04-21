<?php
namespace Enhavo\Bundle\SearchBundle\EventListener;

use Enhavo\Bundle\SearchBundle\Entity\Dataset;
use Enhavo\Bundle\SearchBundle\Entity\Index;
use Enhavo\Bundle\SearchBundle\Entity\Total;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\EventDispatcher\GenericEvent;

class DeleteListener
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function onDelete(GenericEvent $event)
    {
        //get the BundleName
        $array = explode('\\', get_class($event->getSubject()));
        $bundle = null;
        foreach($array as $part) {
            if(strpos($part, 'Bundle', 1)){
                $bundle = $part;
                break;
            }
        }

        //find the right dataset
        $datasetRepository = $this->em->getRepository('EnhavoSearchBundle:Dataset');
        $dataset = $datasetRepository->findOneBy(array(
            'bundle' => $bundle,
            'reference' => $event->getSubject()->getId()
        ));

        //find the belonging indexes
        $indexRepository = $this->em->getRepository('EnhavoSearchBundle:Index');
        $index = $indexRepository->findBy(array(
            'dataset' => $dataset
        ));

        //remove indexed
        foreach($index as $currentIndexToDelete) {
            $word = $currentIndexToDelete->getWord();
            $this->em->remove($currentIndexToDelete);
            $this->em->flush();
            $this->search_dirty($word);
        }

        //remove dataset
        $this->em->remove($dataset);
        $this->em->flush();
        $this->search_update_totals();
    }

    /**
     * Marks a word as "dirty" (changed), or retrieves the list of dirty words.
     *
     * This is used during indexing (cron). Words that are dirty have outdated
     * total counts in the search_total table, and need to be recounted.
     */
    function search_dirty($word = NULL) {
        global $dirty;
        if ($word !== NULL) {
            $dirty[$word] = TRUE;
        }
        else {
            return $dirty;
        }
    }

    /**
     * Updates the score column in the search_total table
     */
    function search_update_totals() {

        //get all of the saved words from seach_dirty
        foreach ($this->search_dirty() as $word => $dummy) {

            // Get total count for the word
            $searchIndexRepository = $this->em->getRepository('EnhavoSearchBundle:Index');
            $total = $searchIndexRepository->sumScoresOfWord($word);

            //get the total count: Normalization according Zipf's law --> the word's value to the search index is inversely proportionate to its overall frequency therein
            $total = log10(1 + 1/(max(1, current($total))));

            //save new score
            $searchTotalRepository =  $this->em->getRepository('EnhavoSearchBundle:Total');
            $currentWord = $searchTotalRepository->findOneBy(array('word' => $word));

            if($currentWord != null) {
                //if the word is already stored in search_total -> remove it and store it with the new score
                $this->em->remove($currentWord);
                $this->em->flush();
            }
            $newTotal = new Total();
            $newTotal->setWord($word);
            $newTotal->setCount($total);
            $this->em->persist($newTotal);
            $this->em->flush();
        }

        //remove words that are removed vom search_index but are still in search_total
        $searchTotalRepository = $this->em->getRepository('EnhavoSearchBundle:Total');
        $wordsToRemove = $searchTotalRepository->getWordsToRemove();
        foreach ($wordsToRemove as $word) {
            $currentWordsToRemove = $searchTotalRepository->findBy(array('word' => $word['realword']));
            if($currentWordsToRemove != null){
                foreach($currentWordsToRemove as $currentWordToRemove) {
                    $this->em->remove($currentWordToRemove);
                    $this->em->flush();
                }
            }
        }
    }
}
