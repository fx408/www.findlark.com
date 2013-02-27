package jrgo

import (
/*"fmt"
"log"
"net/http"
"reflect"*/
)

type JrBase struct {
	Name       string
	Version    string
	postParams map[string]interface{}
	getParams  map[string]interface{}
	pathParams []string
}

var (
	JrApp    = JrBase{Name: "JR", Version: "1.0.0"}
	JrServer = Server{}
	//JrController = Controller{}
	JrRouting = Routing{}
)

func init() {

}
