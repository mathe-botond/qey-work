<?php
namespace QeyWork\Common;

/**
 * @author Dexx
 */
interface IWebsiteBuilder {
    /** @return IRenderer */
    public function getAsRenderer();

    /** @return IRunner */
    public function getAsProcessor();
}
