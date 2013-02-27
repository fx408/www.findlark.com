package gii

import (
	"fmt"
	"jrgo"
)

type GiiController struct {
	jrgo.Controller
}

func (this *GiiController) ActionIndex() {
	fmt.Println("this is gii index")
}

func init() {
	jrgo.JrRouting.Register(&GiiController{controllerName: "gii"})
}
