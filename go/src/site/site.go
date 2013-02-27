package main

import (
	"fmt"
	"jrgo"
	//gii "jrgo/gii"
	//"reflect"
)

type MainController struct {
	jrgo.Controller
	Name string
	//params map[string]string

	//jrgo.Controller
	//controllerType reflect.Type
}

type SiteController struct {
	jrgo.Controller
	Name string
}

func (this *MainController) ActionIndex() {

	form := this.Ctx.Request.Form
	for k, v := range form {
		this.Data[k] = v
	}

	fmt.Println("->controllerName: ", this.Name)
}

func (this *SiteController) ActionIndex() {
	this.Data = this.Form
	fmt.Println("->controllerName: ", this.Data)
}

func main() {

	jrgo.JrRouting.Register(&MainController{Name: "main"})
	jrgo.JrRouting.Register(&SiteController{Name: "site"})

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
