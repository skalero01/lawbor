<?php

namespace App\Services\DocumentAnalysis;

/**
 * Manages text splitting into processable chunks based on configured size limits
 */
class TextChunkManager
{
    private int $chunkSize;

    public function __construct(int $chunkSize = 6000)
    {
        $this->chunkSize = $chunkSize;
    }
    
    /**
     * Get the configured chunk size
     */
    public function getChunkSize(): int
    {
        return $this->chunkSize;
    }

    /**
     * Split text into chunks respecting paragraph boundaries when possible
     */
    public function splitTextIntoChunks(string $text): array
    {
        $paragraphs = preg_split('/\n\s*\n/', $text);
        $chunks = [];
        $currentChunk = '';

        foreach ($paragraphs as $paragraph) {
            // Paragraph too large - split by sentences
            if (mb_strlen($paragraph) > $this->chunkSize) {
                $this->saveCurrentChunk($chunks, $currentChunk);
                $currentChunk = '';
                
                $chunks = array_merge(
                    $chunks, 
                    $this->splitParagraphIntoSentenceChunks($paragraph)
                );
                continue;
            }
            
            // Adding paragraph would exceed chunk size
            if (!empty($currentChunk) && 
                 mb_strlen($currentChunk . "\n\n" . $paragraph) > $this->chunkSize) {
                $chunks[] = $currentChunk;
                $currentChunk = $paragraph;
                continue;
            }
            
            // Paragraph fits in current chunk
            $separator = empty($currentChunk) ? '' : "\n\n";
            $currentChunk .= $separator . $paragraph;
        }

        $this->saveCurrentChunk($chunks, $currentChunk);

        return $chunks;
    }
    
    /**
     * Save non-empty chunk to the chunks array
     */
    private function saveCurrentChunk(array &$chunks, string $chunk): void
    {
        if (!empty($chunk)) {
            $chunks[] = $chunk;
        }
    }
    
    /**
     * Split large paragraphs into smaller chunks based on sentences
     */
    private function splitParagraphIntoSentenceChunks(string $paragraph): array
    {
        $sentenceChunks = [];
        $sentences = preg_split('/(\.|\?|\!)\s+/', $paragraph, -1, PREG_SPLIT_DELIM_CAPTURE);
        $currentChunk = '';
        
        for ($i = 0; $i < count($sentences); $i += 2) {
            $sentence = $i < count($sentences) - 1 
                ? $sentences[$i] . $sentences[$i + 1] 
                : $sentences[$i];
            
            if (!empty($currentChunk) && mb_strlen($currentChunk . $sentence) > $this->chunkSize) {
                $sentenceChunks[] = $currentChunk;
                $currentChunk = $sentence;
            } else {
                $currentChunk .= $sentence;
            }
        }
        
        $this->saveCurrentChunk($sentenceChunks, $currentChunk);
        
        return $sentenceChunks;
    }
    
    /**
     * Validate chunks are non-empty
     */
    public function validateChunks(array $chunks): bool
    {
        if (empty($chunks)) {
            return false;
        }
        
        foreach ($chunks as $chunk) {
            if (empty($chunk)) {
                return false;
            }
        }
        
        return true;
    }
}
