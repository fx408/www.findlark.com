package main

import (
	"fmt"
	"jrgo"
	"reflect"
)

type MainController struct {
	jrgo.Controller
	Action string
	//params map[string]string

	//jrgo.Controller
	controllerType reflect.Type
}

type SiteController struct {
	jrgo.Controller
	Action string

	controllerType reflect.Type
}

func (this *MainController) ActionIndex() {

	fmt.Println("action: ", this.Action)
}

func (this *SiteController) ActionIndex() {

	fmt.Println("site controller -> action index: ", this.Name)
}

func main() {
	m := MainController{Action: "main"}
	s := SiteController{Action: "site"}

	m.ActionIndex()
	s.ActionIndex()

	jrgo.JrRouting.Register("main", &m)
	jrgo.JrRouting.Register("site", &s)

	jrgo.JrServer.StartServer()
	//jrgo.Add("site", &SiteController{})

	//m.StartServer()

	/*
		m := MainController{action: "index"}
		s := SiteController{action: "index"}
		s.controllerType = reflect.TypeOf(s)

		m.ActionIndex()
		jrgo.Add("site.Index", MainController{})

		params["method"] = "site"
		params["action"] = "index"

		vc := reflect.New(s.controllerType)
		in := make([]reflect.Value, 0)
		method := vc.MethodByName("ActionIndex")
		method.Call(in)
	*/
}
