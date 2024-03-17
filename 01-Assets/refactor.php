<?php
 //^ BEFORE
            if (!$contactTwitter) {
                $contactTwitter = new Contact();
                $contactTwitter->setUser($artist); 
                $contactTwitter->setName("Twitter"); 
                $contactTwitter->setIcon('<i class="fa-brands fa-twitter"></i>');
                $contactTwitter->setUrl($artistTwitter);
                $entityManager->persist($contactTwitter);
                $entityManager->flush($contactTwitter);
            } elseif ($contactTwitter) {
                $contactTwitter->setUrl($artistTwitter);
                $entityManager->persist($contactTwitter);
                $entityManager->flush($contactTwitter);
            }

            // DRIBBbLE
            if (!$contactDribbble) {
                $contactDribbble = new Contact();
                $contactDribbble->setUser($artist); 
                $contactDribbble->setName("Dribbble");
                $contactDribbble->setIcon('<i class="fa-brands fa-dribbble"></i>');
                $contactDribbble->setUrl($artistDribbble);
                $entityManager->persist($contactDribbble);
                $entityManager->flush($contactDribbble);
            } elseif ($contactDribbble) {
                $contactDribbble->setUrl($artistDribbble);
                $entityManager->persist($contactDribbble);
                $entityManager->flush($contactDribbble);
            }

            // BEHANCE
            if (!$contactBehance) {
                $contactBehance = new Contact();
                $contactBehance->setUser($artist); 
                $contactBehance->setName("Behance"); 
                $contactBehance->setIcon('<i class="fa-brands fa-square-behance"></i>');
                $contactBehance->setUrl($artistBehance);
                $entityManager->persist($contactBehance);
                $entityManager->flush($contactBehance);
            } elseif ($contactBehance) {
                $contactBehance->setUrl($artistBehance);
                $entityManager->persist($contactBehance);
                $entityManager->flush($contactBehance);
            }

            // BEHANCE
            if (!$contactInstagram) {
                $contactInstagram = new Contact();
                $contactInstagram->setUser($artist); 
                $contactInstagram->setName("Behance"); 
                $contactInstagram->setIcon('<i class="fa-brands fa-square-behance"></i>');
                $contactInstagram->setUrl($artistInstagram);
                $entityManager->persist($contactInstagram);
                $entityManager->flush($contactInstagram);
            } elseif ($contactInstagram) {
                $contactInstagram->setUrl($artistInstagram);
                $entityManager->persist($contactInstagram);
                $entityManager->flush($contactInstagram);
            }



//^ REFACTOR
function createOrUpdateContact($entityManager, $artist, $name, $icon, $url, &$contactVariable, $artistVariable) {
    if (!$contactVariable) {
        $contact = new Contact();
        $contact->setUser($artist);
        $contact->setName($name);
        $contact->setIcon($icon);
        $contact->setUrl($artistVariable);
        $entityManager->persist($contact);
    } else {
        $contact = $contactVariable;
        $contact->setUrl($artistVariable);
    }
    $entityManager->flush($contact);
    }
    
createOrUpdateContact($entityManager, $artist, "Twitter", '<i class="fa-brands fa-twitter"></i>', $artistTwitter, $contactTwitter, $artistTwitter);
createOrUpdateContact($entityManager, $artist, "Dribbble", '<i class="fa-brands fa-dribbble"></i>', $artistDribbble, $contactDribbble, $artistDribbble);
createOrUpdateContact($entityManager, $artist, "Behance", '<i class="fa-brands fa-square-behance"></i>', $artistBehance, $contactBehance, $artistBehance);
createOrUpdateContact($entityManager, $artist, "Instagram", '<i class="fa-brands fa-instagram"></i>', $artistInstagram, $contactInstagram, $artistInstagram);

// ^ _____________________________________________________________